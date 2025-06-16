<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\MainController as MainController;
use App\Http\Requests\CreateItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Repositories\ItemRepository;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\Items;
use App\Models\ItemOption;
use App\Models\AddonType;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables as Datatables;
use Flash;
use Auth;

class ItemController extends MainController {

    private $repository;
    private $uploads_path = "uploads/items";
    private $uploads_variant = "uploads/items/variants";
    private $views_path = "restaurants.items";
    private $home_route = "items.index";
    private $create_route = "items.create";
    private $edit_route = "items.edit";
    private $view_route = "items.show";
    private $delete_route = "items.destroy";
    private $msg_created = "Item added successfully.";
    private $msg_updated = "Item updated successfully.";
    private $msg_deleted = "Item deleted successfully.";
    private $msg_not_found = "Item not found. Please try again.";
    private $msg_required = "Please fill all required fields.";
    private $msg_exists = "Record Already Exists with same Item name";
    private $list_permission = "items-listing";
    private $add_permission = "items-add";
    private $edit_permission = "items-edit";
    private $view_permission = "items-view";
    private $status_permission = "items-status";
    private $delete_permission = "items-delete";
    private $list_permission_error_message = "Error: You are not authorized to View Listings of Items. Please Contact Administrator.";
    private $add_permission_error_message = "Error: You are not authorized to Add Item. Please Contact Administrator.";
    private $edit_permission_error_message = "Error: You are not authorized to Update Item. Please Contact Administrator.";
    private $view_permission_error_message = "Error: You are not authorized to View Item details. Please Contact Administrator.";
    private $status_permission_error_message = "Error: You are not authorized to change status of Item. Please Contact Administrator.";
    private $delete_permission_error_message = "Error: You are not authorized to Delete Item. Please Contact Administrator.";


    public function __construct(ItemRepository $repository) {
        $this->repository = $repository;
    }

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
        items_availability_automatically();
        
        $Auth_User = Auth::user();
        
        $filter_array = ['-1'=> 'All', '1' => 'Active', '0' => 'Inactive'];
        $filter_status = -1;
        $list_title = 'Items';
        $recordsExists = 0;
        switch($list_type){
            case 'active':
                $filter_status = 1;
                $recordsExists = Items::leftJoin('menus', 'items.menu_id', '=', 'menus.id')
                        ->where('menus.rest_id', $Auth_User->rest_id)
                        ->where('items.status', $this->_ACTIVE)->exists();
                $list_title = 'Active '.$list_title;
                break;

            case 'inactive':
                $filter_status = 0;
                $recordsExists = Items::leftJoin('menus', 'items.menu_id', '=', 'menus.id')
                        ->where('menus.rest_id', $Auth_User->rest_id)
                        ->where('items.status', $this->_IN_ACTIVE)->exists();
                $list_title = 'InActive '.$list_title;
                break;

            default:
                $recordsExists = Items::leftJoin('menus', 'items.menu_id', '=', 'menus.id')
                        ->where('menus.rest_id', $Auth_User->rest_id)->exists();
                $list_title = 'All '.$list_title;
                break;            
        }
        
        $restaurants_array = Restaurant::where('status', 1)
                ->orderBy('name', 'asc')
                ->pluck('name', 'id');

        $menus_array = Menu::where('status', 1)
                ->orderBy('title', 'asc')
                ->pluck('title', 'id');

        return view($this->views_path . '.listing', compact("recordsExists", "list_title", "filter_status", "filter_array", "restaurants_array", "menus_array"));
    }

    public function datatable(Request $request) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->list_permission) || $Auth_User->can('all')) {
            return $Auth_User->rest_id == 0 ? $this->admin_datatable($request) : $this->restaurant_datatable($request);
        }

        Flash::error($this->list_permission_error_message);
        return redirect()->route($this->redirect_home);
    }

    public function admin_datatable(Request $request) {
        $Records = Items::leftJoin('menus', 'items.menu_id', '=', 'menus.id')
                ->leftJoin('restaurants', 'menus.rest_id', '=', 'restaurants.id')
                ->select(['items.id', 'items.name', 'items.ar_name', 'items.menu_id', 'items.price', 'items.discount', 'items.availability as item_availability', 'items.status', 'menus.title as menu_name', 'menus.rest_id', 'restaurants.name as rest_name']);

        $response = Datatables::of($Records)
                ->filter(function ($query) use ($request) {
                    if ($request->has('name')) {
                        $query->where('items.name', 'like', "%{$request->name}%");
                    }
                    if ($request->has('ar_name')) {
                        $query->where('items.ar_name', 'like', "%{$request->ar_name}%");
                    }
                    if ($request->has('price')) {
                        $query->where('items.price', '>=', $request->price);
                    }
                    if ($request->has('menu_id') && $request->menu_id != -1) {
                        $query->where('menus.title', 'like', "%{$request->menu_id}%");
                    }
                    if ($request->has('status') && $request->status != -1) {
                        $query->where('items.status', $request->status);
                    }
                    if ($request->has('rest_id') && $request->rest_id != -1) {
                        $query->where('menus.rest_id', $request->rest_id);
                    }
                    if ($request->has('availability') && $request->availability != -1) {
                        $query->where('items.availability', $request->availability);
                    }
                })
                ->addColumn('rest_id', fn($Records) => $Records->rest_name)
                ->addColumn('name', fn($Records) => $Records->name)
                ->addColumn('availability', function ($Records) {
                    return $this->getAvailabilityDropdown($Records);
                })
                ->addColumn('status', function ($Records) {
                    return $this->getStatusDropdown($Records);
                })
                ->addColumn('price', fn($Records) => get_decimal($Records->price))
                ->addColumn('action', function ($Records) {
                    return $this->getActionButtons($Records);
                })
                ->rawColumns(['rest_id', 'name', 'availability', 'status', 'action'])
                ->setRowId(fn($Records) => 'myDtRow' . $Records->id)
                ->make(true);

        return $response;
    }

    public function restaurant_datatable(Request $request) {
        $Auth_User = Auth::user();
        $Records = Items::leftJoin('menus', 'items.menu_id', '=', 'menus.id')
                ->leftJoin('restaurants', 'menus.rest_id', '=', 'restaurants.id')
                ->select(['items.id', 'items.name', 'items.ar_name', 'items.menu_id', 'items.price', 'items.discount', 'items.availability as item_availability', 'items.status', 'menus.title as menu_name', 'menus.rest_id', 'restaurants.name as rest_name'])
                ->where('menus.rest_id', '=', $Auth_User->rest_id);

        $response = Datatables::of($Records)
                ->filter(function ($query) use ($request) {
                    if ($request->has('name')) {
                        $query->where('items.name', 'like', "%{$request->name}%");
                    }
                    if ($request->has('ar_name')) {
                        $query->where('items.ar_name', 'like', "%{$request->ar_name}%");
                    }
                    if ($request->has('price') && is_numeric($request->price)) {
                        $query->where('items.price', '>=', $request->price);
                    }
                    if ($request->has('discount') && is_numeric($request->discount)) {
                        $query->where('items.discount', '>=', $request->discount);
                    }
                    if ($request->has('menu_id') && $request->menu_id != -1) {
                        $query->where('menus.id', '=', $request->menu_id);
                    }
                    if ($request->has('availability') && $request->availability != -1) {
                        $query->where('items.availability', $request->availability);
                    }
                    if ($request->has('status') && $request->status != -1) {
                        $query->where('items.status', $request->status);
                    }
                })
                ->addColumn('name', fn($Records) => $Records->name)
                ->addColumn('price', fn($Records) => get_decimal($Records->price))
                ->addColumn('availability', function ($Records) {
                    return $this->getAvailabilityDropdown($Records);
                })
                ->addColumn('status', function ($Records) {
                    return $this->getStatusDropdown($Records);
                })
                ->addColumn('action', function ($Records) {
                    return $this->getActionButtons($Records);
                })
                ->rawColumns(['availability', 'status', 'action'])
                ->make(true);

        return $response;
    }

    private function getAvailabilityDropdown($Records) {
        $availability = $Records->item_availability;
        $record_id = $Records->id;
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $buttonClass = $availability == 1 ? 'btn-success' : 'btn-danger';
            $title = $availability == 1 ? 'Make Item Not Available' : 'Make Item Available';
            $options = $availability == 1 ? ['UnAvailable', 'UnAvailable for 30 Mins', 'UnAvailable for 60 Mins', 'UnAvailable for 90 Mins', 'UnAvailable for 2 Hrs', 'UnAvailable for 3 Hrs', 'UnAvailable for 4 Hrs'] : ['Available'];

            $dropdown = '<div class="dropdown">
            <a href="#" class="btn ' . $buttonClass . ' dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="fa fa-power-off "></span> Change
            </a>
            <div class="dropdown-menu" x-placement="bottom-start">';

            foreach ($options as $option) {
                $route = $availability == 1 ? route('item-not-available', $record_id) : route('item-available', $record_id); 
                $dropdown .= '<a class="dropdown-item" href="' . $route . '" title="' . $option . '">' . $option . '</a>';
            }

            $dropdown .= '</div></div>';
            return $dropdown;
        }

        return $availability == 1 ? '<span class="fa fa-power-off "></span>' : '<span class="fa fa-power-off"></span>';
    }

    private function getStatusDropdown($Records) {
        $status = $Records->status;
        $record_id = $Records->id;
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $buttonClass = $status == 1 ? 'btn-success' : 'btn-danger';
            $title = $status == 1 ? 'Make Item Inactive' : 'Make Item Active';
            $route = $status == 1 ? route('item-inactive', $record_id) : route('item-active', $record_id);

            return '<a href="' . $route . '" class="btn ' . $buttonClass . ' btn-sm" title="' . $title . '"><span class="fa fa-power-off"></span></a>';
        }

        return $status == 1 ? '<a href="javascript:void(0)" class="btn btn-success btn-sm"><span class="fa fa-power-off "></span></a>' : '<a href="javascript:void(0)" class="btn btn-danger btn-sm"><span class="fa fa-power-off"></span></a>';
    }

    public function getActionButtons($record) {
        $action_buttons = "<div class='btn-group' role='group' aria-label='Horizontal Info'>";
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('items.show', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-eye"></i></a>';
        }

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('items.edit', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>';
        }

        /* if ($this->hasPermission('delete')) {
          $action_buttons .= '<form action="' . route('items.destroy', $record->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure you want to delete this item?\')">';
          $action_buttons .= csrf_field();
          $action_buttons .= method_field('DELETE');
          $action_buttons .= '<button type="submit" class="btn btn-sm btn-danger">Delete</button>';
          $action_buttons .= '</form>';
          }

          if ($record->availability == 'available') {
          $action_buttons .= '<a href="' . route('item-not-available', $record->id) . '" class="btn btn-sm btn-warning">Mark Unavailable</a>';
          }
          else {
          $action_buttons .= '<a href="' . route('item-available', $record->id) . '" class="btn btn-sm btn-success">Mark Available</a>';
          }

          if ($record->status == 'active') {
          $action_buttons .= '<a href="' . route('item-inactive', $record->id) . '" class="btn btn-sm btn-danger">Make Inactive</a>';
          }
          else {
          $action_buttons .= '<a href="' . route('item-active', $record->id) . '" class="btn btn-sm btn-success">Make Active</a>';
          } */
        $action_buttons .= "</div>";

        return $action_buttons;
    }

    public function create() {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->add_permission) && !$Auth_User->can('all')) {
            Flash::error($this->add_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $restaurant = Restaurant::where('status', 1)->orderby('name', 'asc')->pluck('name', 'id');
        $addon_types = AddonType::where('status', 1)->orderby('title', 'asc')->pluck('title', 'id');
        $menus = $this->getMenus($Auth_User->rest_id);

        return view($this->views_path . '.create', compact('restaurant', 'addon_types', 'menus'));
    }

    public function store(CreateItemRequest $request) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->add_permission) && !$Auth_User->can('all')) {
            Flash::error($this->add_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $input = $request->all();
        $Model_data = new Items();

        if ($request->hasFile('image')) {
            $input['image'] = $this->uploadImage($request->file('image'));
            $Model_data->image = $input['image'];
        }

        $Model_data->fill([
            'name'           => $input['name'],
            'menu_id'        => $input['menu_id'],
            'description'    => $input['description'],
            'price'          => get_decimal($input['price']),
            'discount'       => $input['discount'] ?? 0,
            'total_value'    => $input['total_value'],
            'selling_status' => $input['selling_status'],
            'availability'   => $input['availability'],
            'created_by'     => $Auth_User->id
        ]);

        $Model_data->save();

        Flash::success($this->msg_created);
        return redirect()->route($this->edit_route, $Model_data->id);
    }

    public function show($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->view_permission) && !$Auth_User->can('all')) {
            Flash::error($this->view_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = Items::find($id);
        if (!$Model_Data || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        $menu_id = $Model_Data->menu_id;
        $menu_model_data = Menu::find($menu_id);
        $item_rest_id = $menu_model_data->rest_id;

        $restaurant = Restaurant::where('status', 1)->orderby('name', 'asc')->pluck('name', 'id');
        $addon_types = AddonType::where('item_id', $id)->where('status', 1)->orderby('title', 'asc')->pluck('title', 'id');
        $menus = $this->getMenus($Auth_User->rest_id);

        $addons_exists = AddonType::where('item_id', $id)->exists();
        $variants = ItemOption::where('item_id', $id)->where('status', 1)->get();

        return view($this->views_path . '.show', compact('Model_Data', 'variants', 'restaurant', 'addon_types', 'menus', 'item_rest_id', 'addons_exists'));
    }

    public function edit($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->edit_permission) && !$Auth_User->can('all')) {
            Flash::error($this->edit_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = Items::find($id);
        if (!$Model_Data || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        $menu_id = $Model_Data->menu_id;
        $menu_model_data = Menu::find($menu_id);
        $item_rest_id = $menu_model_data->rest_id;

        $restaurant = Restaurant::where('status', 1)->orderby('name', 'asc')->pluck('name', 'id');
        $addon_types = AddonType::where('item_id', $id)->where('status', 1)->orderby('title', 'asc')->pluck('title', 'id');
        $menus = $this->getMenus($Auth_User->rest_id);

        $addons_exists = AddonType::where('item_id', $id)->exists();
        $variants = ItemOption::where('item_id', $id)->where('status', 1)->get();

        return view($this->views_path . '.edit', compact('Model_Data', 'addons_exists', 'variants', 'restaurant', 'addon_types', 'menus', 'item_rest_id'));
    }

    public function update($id, UpdateItemRequest $request) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->edit_permission) && !$Auth_User->can('all')) {
            Flash::error($this->edit_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = Items::find($id);
        if (!$Model_Data || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        $input = $request->all();
        $input['discount'] = $input['discount'] ?? 0;

        if ($request->hasFile('image')) {
            $input['image'] = $this->uploadImage($request->file('image'));
        }

        $this->repository->update($input, $id);

        Flash::success($this->msg_updated);
        return redirect()->route($this->edit_route, $id);
    }

    private function uploadImage($image) {
        $uploads_path = $this->uploads_path;
        if (!is_dir($uploads_path)) {
            mkdir($uploads_path, 0777, true);
            copy($this->_UPLOADS_ROOT . "/index.html", $uploads_path . "/index.html");
        }

        $itemImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
        $image->move($uploads_path, $itemImage);

        return $itemImage;
    }

    private function getMenus($rest_id) {
        $query = Menu::where('status', 1)->orderby('title', 'asc');
        return $rest_id == 0 ? $query->orderby('rest_id', 'asc')->pluck('title', 'id') : $query->where('rest_id', $rest_id)->pluck('title', 'id');
    }

    public function update_addons(Request $request, $id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->edit_permission) && !$Auth_User->can('all')) {
            Flash::error($this->edit_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $item_id = $id;
        $Model_Data = Items::find($id);

        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        $input = $request->all();
        ItemOption::where('item_id', $item_id)->where('status', 1)->update(['status' => 0]);

        if (isset($input['variations']) && $input['variations'] === 'on') {
            $is_variant_valid = true;
            foreach ($input['var_name'] as $i => $var_name) {
                if (isset($input['type_id'][$i], $var_name, $input['var_price'][$i])) {
                    $variant = new ItemOption([
                        'item_id'        => $item_id,
                        'type_id'        => $input['type_id'][$i],
                        'name'           => $var_name,
                        'price'          => get_decimal($input['var_price'][$i]),
                        'description'    => '',
                        'ar_description' => ''
                    ]);
                    $variant->save();
                }
                else {
                    $is_variant_valid = false;
                }
            }

            if ($is_variant_valid) {
                $items = Items::find($item_id);
                $items->update(['has_options' => 1, 'variations' => 1]);
            }
        }

        Flash::success('Addons for Item Updated Successfully.');
        return redirect()->route($this->edit_route, $id);
    }

    public function destroy($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->delete_permission) && !$Auth_User->can('all')) {
            Flash::error($this->delete_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = $this->repository->find($id);

        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        Flash::success($this->msg_deleted);
        return redirect(route($this->home_route));
    }

    public function makeInActive($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = $this->repository->find($id);

        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        Items::where('id', $id)->update(['status' => 0, 'updated_by' => $Auth_User->id]);

        Flash::success('Item Deactivated successfully.');
        return redirect(route($this->home_route));
    }

    public function makeNotAvailable($id, $minutes = 0) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = Items::find($id);

        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        $message = $minutes ? "Item set to Not Available for $minutes minutes successfully." : "Item set to Not Available successfully.";
        Items::where('id', $id)->update(['availability' => 0, 're_available_time' => $minutes ? time() + ($minutes * 60) : 0, 'updated_by' => $Auth_User->id]);

        Flash::success($message);
        return redirect(route($this->home_route));
    }

    public function makeActive($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = $this->repository->find($id);

        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        Items::where('id', $id)->update(['status' => 1, 'updated_by' => $Auth_User->id]);

        Flash::success('Item Activated successfully.');
        return redirect(route($this->home_route));
    }

    public function makeAvailable($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = Items::find($id);

        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        Items::where('id', $id)->update(['availability' => 1, 're_available_time' => 0, 'updated_by' => $Auth_User->id]);

        Flash::success('Item set to Available successfully.');
        return redirect(route($this->home_route));
    }

    public function is_not_authorized($id, $Auth_User) {
        $rest_id = $Auth_User->rest_id;

        if ($rest_id == 0) {
            return true;
        }

        return !DB::table('items')
                        ->join('menus', 'items.menu_id', '=', 'menus.id')
                        ->where('menus.rest_id', $rest_id)
                        ->where('items.id', $id)
                        ->exists();
    }
}
