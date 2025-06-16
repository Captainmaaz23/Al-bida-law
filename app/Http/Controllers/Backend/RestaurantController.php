<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\MainController as MainController;
use App\Models\Items;
use App\Repositories\RestaurantRepository;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables as Datatables;
use Flash;
use Auth;
use File;
use Carbon\Carbon;

class RestaurantController extends MainController {

    private $repository;
    private $uploads_path = "uploads/restaurants";
    private $qrcode_path = "uploads/restaurants/qrcodes";
    private $views_path = "restaurants";
    private $kitchen_route = "kitchenDashboard";
    private $home_route = "restaurants.index";
    private $create_route = "restaurants.create";
    private $edit_route = "restaurants.edit";
    private $view_route = "restaurants.show";
    private $delete_route = "restaurants.destroy";
    private $menu_route = "menus.show";
    private $pdf_route = "pdf-files.show";
    private $msg_created = "Restaurant added successfully.";
    private $msg_updated = "Restaurant updated successfully.";
    private $msg_deleted = "Restaurant deleted successfully.";
    private $msg_not_found = "Restaurant not found. Please try again.";
    private $msg_required = "Please fill all required fields.";
    private $msg_exists = "Record Already Exists with same Restaurant name";
    private $list_permission = "restaurants-listing";
    private $add_permission = "restaurants-add";
    private $edit_permission = "restaurants-edit";
    private $view_permission = "restaurants-view";
    private $status_permission = "restaurants-status";
    private $delete_permission = "restaurants-delete";
    private $list_permission_error_message = "Error: You are not authorized to View Listings of Restaurants. Please Contact Administrator.";
    private $add_permission_error_message = "Error: You are not authorized to Add Restaurant. Please Contact Administrator.";
    private $edit_permission_error_message = "Error: You are not authorized to Update Restaurant. Please Contact Administrator.";
    private $view_permission_error_message = "Error: You are not authorized to View Restaurant details. Please Contact Administrator.";
    private $status_permission_error_message = "Error: You are not authorized to change status of Restaurant. Please Contact Administrator.";
    private $delete_permission_error_message = "Error: You are not authorized to Delete Restaurant. Please Contact Administrator.";

    public function __construct(RestaurantRepository $restaurant) {
        $this->repository = $restaurant;
    }

    public function get_opening_time(Request $request) {
        return is_restaurant_open(Restaurant::find($request->rest_id));
    }

    public function index() {
        if (Auth::user()->can($this->list_permission) || Auth::user()->can('all')) {
            return redirect()->route($this->view_route, 1);
        }
        Flash::error($this->list_permission_error_message);
        return redirect()->route($this->redirect_home);
    }

    public function menu_datatable(Request $request, $id) {
        if (Auth::user()->can($this->list_permission) || Auth::user()->can('all')) {
            $Records = Menu::where('rest_id', $id)->where('status', 1)->get();

            return Datatables::of($Records)
                            ->addColumn('order', fn($Records) => $Records->is_order)
                            ->addColumn('title', fn($Records) => $Records->title)
                            ->addColumn('items', fn($Records) => Items::where('menu_id', $Records->id)->where('status', 1)->count())
                            ->addColumn('created_at', fn($Records) => $Records->created_at)
                            ->addColumn('updated_at', fn($Records) => $Records->updated_at)
                            ->addColumn('action', fn($Records) => '<a class="btn btn-outline-primary" href="' . route($this->menu_route, $Records->id) . '" title="View Details" target="_blank"><i class="fa fa-eye"></i></a>')
                            ->rawColumns(['count', 'title', 'items', 'created_at', 'updated_at', 'action'])
                            ->setRowId(fn($Records) => 'myDtRow' . $Records->id)
                            ->make(true);
        }

        Flash::error($this->list_permission_error_message);
        return redirect()->route($this->redirect_home);
    }

    public function order_datatable(Request $request, $id) {
        $Records = Order::leftJoin('restaurants', 'orders.rest_id', '=', 'restaurants.id')
                ->leftJoin('app_users', 'orders.user_id', '=', 'app_users.id')
                ->select(['orders.id', 'orders.order_no', 'orders.rest_id', 'orders.pay_method', 'app_users.name as user_name', 'orders.order_value', 'orders.user_id', 'orders.status', 'restaurants.name as rest_name'])
                ->where('orders.rest_id', '=', $id)
                ->where('orders.status', '>=', '3');

        return Datatables::of($Records)
                        ->filter(fn($query) => $query->when($request->has('rest_id') && $request->get('rest_id') != -1, fn($q) => $q->where('orders.rest_id', $request->get('rest_id')))
                                ->when($request->has('order_no') && !empty($request->order_no), fn($q) => $q->where('orders.order_no', 'like', "%{$request->get('order_no')}%"))
                                ->when($request->has('order_value') && !empty($request->order_value), fn($q) => $q->where('orders.order_value', '>', $request->get('order_value')))
                                ->when($request->has('order_status') && $request->get('order_status') != -1, fn($q) => $q->where('orders.status', $request->get('order_status'))))
                        ->addColumn('rest_name', fn($Records) => $Records->rest_name)
                        ->addColumn('order_no', fn($Records) => Auth::user()->can('orders-view') || Auth::user()->can('all') ? '<a href="' . route('orders.show', $Records->id) . '" title="View Order Details">' . $Records->order_no . '</a>' : $Records->order_no)
                        ->addColumn('order_value', fn($Records) => $Records->order_value)
                        ->addColumn('status', fn($Records) => $this->dt_get_order_status($Records->status))
                        ->addColumn('action', fn($Records) => $this->get_order_action($Records->id))
                        ->rawColumns(['rest_id', 'order_no', 'order_value', 'status', 'action'])
                        ->setRowId(fn($Records) => 'myDtRow' . $Records->id)
                        ->make(true);
    }

    private function dt_get_order_status($status) {
        $statuses = [3 => "Confirmed", 4 => "Declined", 5 => "Accepted", 6 => "Preparing", 7 => "Ready", 8 => "Picked", 9 => "Collected"];
        return $statuses[$status] ?? 'Unknown';
    }

    private function get_order_action($id) {
        return '<div class="btn-group" role="group" aria-label="Horizontal Info">
        <a class="btn btn-outline-primary" href="' . route('orders.show', $id) . '" title="View Details" target="_blank">
        <i class="fa fa-eye"></i></a>
    </div>';
    }

    public function is_not_authorized($id, $Auth_User) {
        return 0;
    }

    public function create() {
        return redirect()->route($this->home_route);
    }

    public function store(Request $request) {
        return redirect()->route($this->home_route);
    }

    public function show($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $Model_Data = Restaurant::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Menus = Menu::where('rest_id', $id)->where('status', 1)->get();
            $menus = Menu::leftJoin('restaurants', 'menus.rest_id', '=', 'restaurants.id')
                    ->select(['menus.id', 'menus.title', 'menus.is_order'])
                    ->where('menus.rest_id', '=', $id)
                    ->where('menus.status', '=', 1)
                    ->orderby('menus.is_order', 'asc')
                    ->get();

            $menus_array = $menu_items_array = [];
            foreach ($menus as $menu) {
                $menuid = $menu->id;
                $menus_array[$menuid] = $menu->title;
                $menu_items_array[$menuid] = Items::select(['items.id'])
                        ->where('menu_id', '=', $menuid)
                        ->where('status', '=', 1)
                        ->orderBy('is_order', 'asc')
                        ->count();
            }

            $activity_status = is_restaurant_open($Model_Data) ? "Open" : "Close";

            $Orders = Order::leftJoin('restaurants', 'orders.rest_id', '=', 'restaurants.id')
                    ->leftJoin('app_users', 'orders.rest_id', '=', 'restaurants.id')
                    ->where(['orders.rest_id' => $id])
                    ->select(['orders.id'])
                    ->get();

            $UserOrders_exists = $Orders->isNotEmpty() ? 1 : 0;

            return view($this->views_path . '.show', compact('Model_Data', 'activity_status', 'Menus', 'menus', 'menus_array', 'menu_items_array', 'UserOrders_exists'));
        }

        Flash::error($this->view_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function edit($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $Model_Data = Restaurant::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Menus = Menu::where('rest_id', $id)->get(['id', 'title', 'ar_title', 'status']);
            return view($this->views_path . '.edit', compact('Model_Data', 'Menus'));
        }

        Flash::error($this->edit_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function update($id, Request $request) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $Model_Data = Restaurant::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $input = $request->all();
            $image = $Model_Data->profile;
            $uploads_path = $this->uploads_path;

            if ($request->hasfile('profile') && $request->profile != null) {
                $file_uploaded = $request->file('profile');
                $image = date('YmdHis') . "." . $file_uploaded->getClientOriginalExtension();
                $file_uploaded->move($uploads_path, $image);

                if ($Model_Data->profile != "") {
                    File::delete($uploads_path . "/" . $Model_Data->profile);
                }
            }

            $input['profile'] = ltrim(rtrim($image));
            $input['updated_by'] = Auth::user()->id;

            $Model_Data->update($input);
            $this->update_qr_code($id);

            Flash::success($this->msg_updated);
            return redirect()->route($this->home_route);
        }

        Flash::error($this->edit_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function destroy($id) {
        return redirect(route($this->home_route));
    }

    public function makeInActive($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Restaurant::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }
            $Model_Data->status = 0;
            $Model_Data->is_featured = 0;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();
            Flash::success('Restaurant InActive successfully.');
            return redirect(route($this->home_route));
        }
        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function makeActive($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Restaurant::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }
            $Model_Data->status = 1;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();
            Flash::success('Restaurant Active successfully.');
            return redirect(route($this->home_route));
        }
        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function addFeatured($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Restaurant::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }
            $Model_Data->is_featured = 1;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();
            Flash::success('Restaurant is now added as featured.');
            return redirect(route($this->home_route));
        }
        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function removeFeatured($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Restaurant::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }
            $Model_Data->is_featured = 0;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();
            Flash::success('Restaurant is removed from featured.');
            return redirect(route($this->home_route));
        }
        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function makeClose($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Restaurant::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }
            $Model_Data->is_open = 0;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();
            Flash::success('Restaurant status updated to Closed successfully.');
            return redirect(route($this->home_route));
        }
        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function makeOpen($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Restaurant::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }
            $Model_Data->is_open = 1;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();
            Flash::success('Restaurant status updated to Opened successfully.');
            return redirect(route($this->home_route));
        }
        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function makeBusy($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Restaurant::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }
            $Model_Data->is_open = 2;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();
            Flash::success('Restaurant status updated to Busy successfully.');
            return redirect(route($this->home_route));
        }
        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function makeKitchenClose($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Restaurant::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->kitchen_route));
            }
            $Model_Data->is_open = 0;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();
            Flash::success('Restaurant Closed successfully.');
            return redirect(route($this->kitchen_route));
        }
        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->kitchen_route);
    }

    public function makeKitchenOpen($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Restaurant::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->kitchen_route));
            }
            $Model_Data->is_open = 1;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();
            Flash::success('Restaurant Opened successfully.');
            return redirect(route($this->kitchen_route));
        }
        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->kitchen_route);
    }

    public function makeKitchenBusy($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Restaurant::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->kitchen_route));
            }
            $Model_Data->is_open = 2;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();
            Flash::success('Restaurant Rush Mode Enabled successfully.');
            return redirect(route($this->kitchen_route));
        }
        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->kitchen_route);
    }

    public function makeKitchenNotBusy($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Restaurant::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->kitchen_route));
            }
            $Model_Data->is_open = 1;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();
            Flash::success('Restaurant Rush Mode Disabled Successfully.');
            return redirect(route($this->kitchen_route));
        }
        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->kitchen_route);
    }

    public function saveOrderSlides($id, Request $request) {
        $Auth_User = Auth::user();
        if ($Auth_User->can("slides-edit") || $Auth_User->can('all')) {
            $Model_Data = Restaurant::find($id);
            if (empty($Model_Data)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }
            $Model_Data->saveSlides($request);
            Flash::success('Slides updated successfully.');
            return redirect(route($this->home_route));
        }
        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->home_route);
    }
}
