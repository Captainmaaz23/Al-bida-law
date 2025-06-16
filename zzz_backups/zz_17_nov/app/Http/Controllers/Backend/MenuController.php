<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\MainController as MainController;
use Auth;
use File;
use Flash;
use Yajra\DataTables\Facades\DataTables as Datatables;
use App\Models\Menu;
use App\Models\User;
use App\Models\Items;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class MenuController extends MainController {

    private $uploads_rest = "uploads/restaurants";
    private $uploads_path = "uploads/menus";
    private $msg_created = "Menu added successfully.";
    private $msg_updated = "Menu updated successfully.";
    private $msg_deleted = "Menu deleted successfully.";
    private $msg_not_found = "Restaurant Menu not found. Please try again.";
    private $msg_required = "Please fill all required fields.";
    private $msg_exists = "Record Already Exists with same Menu name";
    private $views_path = "restaurants.menus";
    private $home_route = "menus.index";
    private $create_route = "menus.create";
    private $edit_route = "menus.edit";
    private $view_route = "menus.show";
    private $delete_route = "menus.destroy";
    private $list_permission = "menus-listing";
    private $add_permission = "menus-add";
    private $edit_permission = "menus-edit";
    private $view_permission = "menus-view";
    private $status_permission = "menus-status";
    private $delete_permission = "menus-delete";
    private $list_permission_error_message = "Error: You are not authorized to View Listings of Restaurant menus. Please Contact Administrator.";
    private $add_permission_error_message = "Error: You are not authorized to Add Restaurant menus. Please Contact Administrator.";
    private $edit_permission_error_message = "Error: You are not authorized to Update Restaurant menus. Please Contact Administrator.";
    private $view_permission_error_message = "Error: You are not authorized to View Restaurant menus details. Please Contact Administrator.";
    private $status_permission_error_message = "Error: You are not authorized to change status of Restaurant menus. Please Contact Administrator.";
    private $delete_permission_error_message = "Error: You are not authorized to Delete Restaurant menus. Please Contact Administrator.";

    public function index($list_type = 'All') {
        return $this->commonListing($list_type);
    }

    public function inactive_listing($list_type = 'inactive') {
        return $this->commonListing($list_type);
    }

    public function active_listing($list_type = 'active') {
        return $this->commonListing($list_type);
    }

    private function commonListing($list_type) {
        if (!$this->userCan($this->list_permission)) {
            Flash::error($this->list_permission_error_message);
            return redirect()->route($this->redirect_home);
        }
        menus_availability_automatically();

        $Auth_User = Auth::user();
        
        $filter_array = ['-1'=> 'All', '1' => 'Active', '0' => 'Inactive'];
        $filter_status = -1;
        $list_title = 'Menus';
        $recordsExists = 0;
        switch($list_type){
            case 'active':
                $filter_status = 1;
                $recordsExists = Menu::where('rest_id', $Auth_User->rest_id)->where('status', $this->_ACTIVE)->exists();
                $list_title = 'Active '.$list_title;
                break;

            case 'inactive':
                $filter_status = 0;
                $recordsExists = Menu::where('rest_id', $Auth_User->rest_id)->where('status', $this->_IN_ACTIVE)->exists();
                $list_title = 'InActive '.$list_title;
                break;

            default:
                $recordsExists = Menu::where('rest_id', $Auth_User->rest_id)->exists();
                $list_title = 'All '.$list_title;
                break;            
        }

        $restaurants_array = Restaurant::select(['id', 'name'])->where('status', 1)->orderby('name', 'asc')->pluck('name', 'id');

        $menus = [];
        if ($Auth_User->rest_id > 0) {
            $menus = Menu::leftJoin('restaurants', 'menus.rest_id', '=', 'restaurants.id')
                    ->select(['menus.id', 'menus.title', 'menus.is_order'])
                    ->where('menus.rest_id', $Auth_User->rest_id)
                    ->where('menus.status', 1)
                    ->orderby('menus.is_order', 'asc')
                    ->get();
        }

        return view($this->views_path . '.listing', compact("recordsExists", "list_title", "filter_status", "filter_array", "restaurants_array", "menus"));
    }

    public function datatable(Request $request) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->list_permission) || $Auth_User->can('all')) {
            if ($Auth_User->rest_id > 0) {
                return $this->admin_datatable($request);
            }
            elseif ($Auth_User->rest_id == 0) {
                return $this->admin_datatable($request);
            }
        }

        Flash::error($this->list_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function admin_datatable(Request $request) {
        $Auth_User = Auth::user();
        $Records = Menu::leftJoin('restaurants', 'menus.rest_id', '=', 'restaurants.id')
                ->select(['menus.rest_id', 'restaurants.name', 'restaurants.arabic_name', 'menus.id', 'menus.title', 'menus.ar_title', 'menus.availability', 'menus.status']);

        $response = Datatables::of($Records)
                ->filter(function ($query) use ($request) {
                    if ($request->has('rest_id') && $request->get('rest_id') != -1) {
                        $query->where('menus.rest_id', '=', "{$request->get('rest_id')}");
                    }
                    if ($request->has('title') && !empty($request->title)) {
                        $query->where('menus.title', 'like', "%{$request->get('title')}%");
                    }
                    if ($request->has('ar_title') && !empty($request->ar_title)) {
                        $query->where('menus.ar_title', 'like', "%{$request->get('ar_title')}%");
                    }
                    if ($request->has('availability') && $request->get('availability') != -1) {
                        $query->where('menus.availability', '=', "{$request->get('availability')}");
                    }
                    if ($request->has('status') && $request->get('status') != -1) {
                        $query->where('menus.status', '=', "{$request->get('status')}");
                    }
                })
                ->addColumn('rest_id', fn($Records) => $Records->name)
                ->addColumn('title', fn($Records) => $Records->title)
                ->addColumn('arabic_name', fn($Records) => $Records->arabic_name)
                ->addColumn('availability', function ($Records) use ($Auth_User) {
                    return $this->renderDropdown($Records->availability, $Records->id, 'availability', $Auth_User);
                })
                ->addColumn('status', function ($Records) use ($Auth_User) {
                    return $this->renderDropdown($Records->status, $Records->id, 'status', $Auth_User);
                })
                ->addColumn('action', function ($Records) use ($Auth_User) {
                    $str = "<div class='btn-group' role='group' aria-label='Actions'>";
                    $str .= $this->renderActionButtons($Records, $Auth_User);
                    $str .= "</div>";
                    return $str;
                })
                ->rawColumns(['title', 'availability', 'status', 'action'])
                ->setRowId(fn($Records) => 'myDtRow' . $Records->id)
                ->make(true);

        return $response;
    }

    private function renderDropdown($value, $id, $type, $Auth_User) {
        $routes = [
            'availability' => [
                1 => route('menu-not-available', $id),
                0 => route('menu-available', $id)
            ],
            'status'       => [
                1 => route('menu-inactive', $id),
                0 => route('menu-active', $id)
            ]
        ];

        $isAuthorized = $Auth_User->can($this->status_permission) || $Auth_User->can('all');
        $statusText = $value == 1 ? 'Change' : 'Change';
        $buttonClass = $value == 1 ? 'btn-success' : 'btn-danger';
        $dropdownText = $value == 1 ? ($type == 'availability' ? 'UnAvailable' : 'Inactive') : ($type == 'availability' ? 'Available' : 'Active');

        if ($isAuthorized) {
            return "<div class='dropdown'>
                    <a href='#' class='btn $buttonClass dropdown-toggle' type='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>$statusText</a>
                    <div class='dropdown-menu'>
                        <a class='dropdown-item' href='{$routes[$type][$value]}'>$dropdownText</a>
                    </div>
                </div>";
        }
        else {
            return $value == 1 ? '<span class="fa fa-power-off"></span>' : '<span class="fa fa-power-off"></span>';
        }
    }

    private function renderActionButtons($Records, $Auth_User) {
        $str = '';
        $relation_count = $this->relation_count($Records->id);

        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $str .= '<a class="btn btn-outline-primary" href="' . route($this->view_route, $Records->id) . '" title="View Details"><i class="fa fa-eye"></i></a>';
        }

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $str .= '<a class="btn btn-outline-primary" href="' . route($this->edit_route, $Records->id) . '" title="Edit Details"><i class="fa fa-edit"></i></a>';
        }

        /*if ($Auth_User->can($this->delete_permission) || $Auth_User->can('all')) {
            $str .= $this->renderDeleteModal($Records, $relation_count);
        }*/

        return $str;
    }

    private function renderDeleteModal($Records, $relation_count) {
        $str = '';
        if ($relation_count == 0) {
            $str .= '<a class="btn btn-outline-warning" href="#" data-toggle="modal" data-target="#m-' . $Records->id . '" title="Delete"><i class="fa fa-trash"></i></a>
                <div id="m-' . $Records->id . '" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="' . route($this->delete_route, $Records->id) . '" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <div class="modal-header"><h5 class="modal-title">Confirm delete record</h5></div>
                                <div class="modal-body text-center p-lg">
                                    <p>Are you sure to delete this record? <br><strong>[ ' . $Records->title . ' ]</strong></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                    <button type="submit" class="btn btn-danger">Yes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>';
        }
        else {
            $str .= '<a class="btn btn-outline-secondary" href="#" data-toggle="modal" data-target="#m-' . $Records->id . '" title="Delete"><i class="fa fa-trash"></i></a>
                <div id="m-' . $Records->id . '" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body text-center p-lg">
                                <p>Record cannot be deleted due to existing relations.<br><strong>[ ' . $Records->title . ' ]</strong></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>';
        }
        return $str;
    }

    public function create() {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->add_permission) || $Auth_User->can('all')) {
            $restaurants_array = $Auth_User->rest_id < 1 ? Restaurant::select(['name', 'id'])->where('status', 1)->orderby('name', 'asc')->pluck('name', 'id') : $Auth_User->rest_id;
            return view($this->views_path . '.create', compact('restaurants_array'));
        }

        Flash::error($this->add_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function store(Request $request) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->add_permission) || $Auth_User->can('all')) {
            $request->validate([
                'rest_id'     => 'required',
                'title'       => 'required',
                'file_upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8048'
            ]);

            $rest_id = get_auth_rest_id($request, $Auth_User);
            $Model_Data = new Menu();
            $Model_Data->rest_id = $rest_id;
            $Model_Data->title = $request->title;
            $Model_Data->availability = $request->availability;

            if ($request->hasFile('file_upload')) {
                $file_uploaded = $request->file('file_upload');
                $image = date('YmdHis') . "." . $file_uploaded->getClientOriginalExtension();
                $uploads_path = $this->uploads_path;

                if (!is_dir($uploads_path)) {
                    mkdir($uploads_path);
                    copy($this->_UPLOADS_ROOT . "/index.html", $uploads_path . "/index.html");
                }

                $file_uploaded->move($uploads_path, $image);
                $Model_Data->icon = $image;
            }

            $Model_Data->created_by = $Auth_User->id;
            $Model_Data->save();

            Flash::success($this->msg_created);
            return redirect()->route($this->home_route);
        }

        Flash::error($this->add_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function show($id) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $Model_Data = Menu::find($id);

            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $rest_id = $Model_Data->rest_id;
            $restaurants_array = Restaurant::select(['id', 'name'])->where('id', $rest_id)->where('status', 1)->orderby('name', 'asc')->pluck('name', 'id');
            $items = Items::leftJoin('menus', 'items.menu_id', '=', 'menus.id')
                    ->leftJoin('restaurants', 'menus.rest_id', '=', 'restaurants.id')
                    ->select(['items.id', 'items.menu_id', 'items.name', 'items.total_value'])
                    ->where('menus.rest_id', $rest_id)
                    ->where('items.menu_id', $id)
                    ->where('restaurants.status', 1)
                    ->where('items.status', 1)
                    ->orderby('items.is_order', 'asc')
                    ->get();

            return view($this->views_path . '.show', compact('Model_Data', 'restaurants_array', 'items'));
        }

        Flash::error($this->view_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function edit($id) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $Model_Data = Menu::find($id);

            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $restaurants_array = Restaurant::select(['id', 'name'])->where('id', $Model_Data->rest_id)->where('status', 1)->orderby('name', 'asc')->pluck('name', 'id');
            return view($this->views_path . '.edit', compact('Model_Data', 'restaurants_array'));
        }

        Flash::error($this->edit_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function update($id, Request $request) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $Model_Data = Menu::find($id);

            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Model_Data->title = $request->title;
            $Model_Data->availability = $request->availability;
            $image = $Model_Data->icon;

            if ($request->hasFile('file_upload')) {
                $file_uploaded = $request->file('file_upload');
                $image = date('YmdHis') . "." . $file_uploaded->getClientOriginalExtension();
                $file_uploaded->move($this->uploads_path, $image);

                if ($Model_Data->icon) {
                    File::delete($this->uploads_path . "/" . $Model_Data->icon);
                }
            }

            $Model_Data->icon = $image;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();

            if ($request->availability == 0) {
                Items::where('menu_id', $id)->update(['availability' => '0']);
            }

            Flash::success($this->msg_updated);
            return redirect()->route($this->home_route);
        }

        Flash::error($this->edit_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function saveOrder($id, Request $request) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $Model_Data = Menu::find($id);

            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $menu_id = $Model_Data->id;
            if (isset($request->item_order)) {
                $input = $request->all();
                $is_order = 0;
                $request_items = $request->item_order;

                foreach ($request_items as $i => $request_item) {
                    if (isset($input['item_order'][$i])) {
                        $is_order++;
                        $item_id = $input['item_order'][$i];
                        $Items = Items::where('id', $item_id)->where('menu_id', $menu_id)->where('status', 1)->get();

                        foreach ($Items as $Item) {
                            $Item->is_order = $is_order;
                            $Item->save();
                        }
                    }
                }
            }

            Flash::success('Menu Items order saved successfully.');
            return redirect(route($this->home_route));
        }
        else {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function destroy($id) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->delete_permission) || $Auth_User->can('all')) {
            $Model_Data = Menu::find($id);

            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $relation_count = $this->relation_count($id);
            if ($relation_count == 0) {
                $Model_Data->delete();
                Flash::success($this->msg_deleted);
            }

            return redirect()->route($this->home_route);
        }
        else {
            Flash::error($this->delete_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function makeInActive($id) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Menu::find($id);

            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Model_Data->status = 0;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();

            Flash::success('Menu Deactivated successfully.');
            return redirect(route($this->home_route));
        }
        else {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function makeNotAvailable($id, $minutes = 0) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Menu::find($id);

            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Model_Data->availability = 0;
            $message = "Menu set to Not Available successfully.";

            if ($minutes != 0) {
                $current_time = time();
                $Model_Data->re_available_time = ($current_time + ($minutes * 60));
                $message = "Menu set to Not Available for $minutes minutes successfully.";
            }
            else {
                $Model_Data->re_available_time = 0;
            }

            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();

            Flash::success($message);
            return redirect(route($this->home_route));
        }
        else {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function makeActive($id) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Menu::find($id);

            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Model_Data->status = 1;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();

            Flash::success('Menu Activated successfully.');
            return redirect(route($this->home_route));
        }
        else {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function makeAvailable($id) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Menu::find($id);

            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Model_Data->availability = 1;
            $Model_Data->re_available_time = 0;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();

            Flash::success('Menu set to Available successfully.');
            return redirect(route($this->home_route));
        }
        else {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function is_not_authorized($id, $Auth_User) {
        $bool = 1;

        if ($Auth_User->rest_id != 0) {
            $rest_id = $Auth_User->rest_id;
            $records = Menu::select(['id'])->where('id', '=', $id)->where('rest_id', '=', $rest_id)->limit(1)->get();
            foreach ($records as $record) {
                $bool = 0;
            }
        }

        return $bool;
    }

    public function relation_count($record_id) {
        return Items::where('menu_id', '=', $record_id)->count();
    }
}
