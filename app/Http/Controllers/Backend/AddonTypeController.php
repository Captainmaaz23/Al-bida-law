<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\MainController as MainController;
use App\Models\Addon;
use App\Models\Restaurant;
use App\Models\Items;
use App\Models\Menu;
use App\Models\AddonType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables as Datatables;
use Flash;
use Response;
use Auth;
use File;

class AddonTypeController extends MainController {

    private $uploads_path = "uploads/addon_types";
    private $views_path = "addon_types";
    private $home_route = "items.index";
    private $create_route = "addon-types.create";
    private $edit_route = "addon-types.edit";
    private $view_route = "addon-types.show";
    private $delete_route = "addon-types.destroy";
    private $msg_created = "Addon type added successfully.";
    private $msg_updated = "Addon type updated successfully.";
    private $msg_deleted = "Addon type deleted successfully.";
    private $msg_not_found = "Addon type not found. Please try again.";
    private $msg_required = "Please fill all required fields.";
    private $msg_exists = "Record Already Exists with same Brand name";
    private $list_permission = "addon-types-listing";
    private $add_permission = "addon-types-add";
    private $edit_permission = "addon-types-edit";
    private $view_permission = "addon-types-view";
    private $status_permission = "addon-types-status";
    private $delete_permission = "addon-types-delete";
    private $list_permission_error_message = "Error: You are not authorized to View Listings of Addon types. Please Contact Administrator.";
    private $add_permission_error_message = "Error: You are not authorized to Add Addon type. Please Contact Administrator.";
    private $edit_permission_error_message = "Error: You are not authorized to Update Addon type. Please Contact Administrator.";
    private $view_permission_error_message = "Error: You are not authorized to View Addon type details. Please Contact Administrator.";
    private $status_permission_error_message = "Error: You are not authorized to change status of Addon type. Please Contact Administrator.";
    private $delete_permission_error_message = "Error: You are not authorized to Delete Addon type. Please Contact Administrator.";

    public function index() {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->list_permission) || $Auth_User->can('all')) {
            $recordsExists = AddonType::where('id', '>=', 1)->limit(1)->exists();
            $restaurants_array = Restaurant::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id');
            $Items = $this->get_items_pluck();

            return view($this->views_path . '.listing', compact("recordsExists", "restaurants_array", "Items"));
        }

        Flash::error($this->list_permission_error_message);
        return redirect()->route($this->redirect_home);
    }

    public function get_items_pluck() {
        $Auth_User = Auth::user();
        $rest_id = $Auth_User->rest_id;

        if ($rest_id == 0) {
            return Items::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id');
        }

        $menus_ids = Menu::where('rest_id', $rest_id)->pluck('id')->toArray();
        array_unshift($menus_ids, 0);

        return Items::whereIn('menu_id', $menus_ids)->where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id');
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
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->list_permission) || $Auth_User->can('all')) {
            $Records = AddonType::leftjoin('items', 'addon_types.item_id', '=', 'items.id')
                    ->leftJoin('menus', 'items.menu_id', '=', 'menus.id')
                    ->leftJoin('restaurants', 'menus.rest_id', '=', 'restaurants.id')
                    ->select(['addon_types.id', 'addon_types.title as name', 'addon_types.title_ar as name_ar', 'addon_types.is_mandatory', 'addon_types.is_multi_select', 'addon_types.max_selection', 'items.name as item_name', 'restaurants.name as rest_name'])
                    ->where('addon_types.status', '=', 1);

            $response = Datatables::of($Records)
                    ->filter(function ($query) use ($request) {
                        if ($request->has('rest_id') && $request->get('rest_id') != -1) {
                            $query->where('menus.rest_id', '=', $request->get('rest_id'));
                        }
                        if ($request->has('item_id') && $request->get('item_id') != -1) {
                            $query->where('addon_types.item_id', '=', $request->get('item_id'));
                        }
                        if ($request->has('name') && !empty($request->name)) {
                            $query->where('addon_types.title', 'like', "%{$request->get('name')}%");
                        }
                        if ($request->has('name_ar') && !empty($request->name_ar)) {
                            $query->where('addon_types.title_ar', 'like', "%{$request->get('name_ar')}%");
                        }
                        if ($request->has('is_mandatory') && $request->get('is_mandatory') != -1) {
                            $query->where('addon_types.is_mandatory', '=', $request->get('is_mandatory'));
                        }
                        if ($request->has('is_multi_select') && $request->get('is_multi_select') != -1) {
                            $query->where('addon_types.is_multi_select', '=', $request->get('is_multi_select'));
                        }
                        if ($request->has('max_selection') && !empty($request->max_selection)) {
                            $query->where('addon_types.max_selection', '>=', $request->get('max_selection'));
                        }
                    })
                    ->addColumn('rest_id', fn($Records) => $Records->rest_name)
                    ->addColumn('item_name', fn($Records) => $Records->item_name)
                    ->addColumn('name', function ($Records) use ($Auth_User) {
                        $title = $Records->name;
                        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
                            $title = '<a href="' . route($this->view_route, $Records->id) . '" title="View Details">' . $title . '</a>';
                        }
                        return $title;
                    })
                    ->addColumn('is_mandatory', function ($Records) use ($Auth_User) {
                        return $this->generateStatusLink('addon-types-disableMandatory', 'addon-types-enableMandatory', $Records, $Auth_User);
                    })
                    ->addColumn('is_multi_select', function ($Records) use ($Auth_User) {
                        return $this->generateStatusLink('addon-types-disableMultiselect', 'addon-types-enableMultiselect', $Records, $Auth_User);
                    })
                    ->addColumn('action', function ($Records) use ($Auth_User) {
                        return $this->generateActionButtons($Records, $Auth_User);
                    })
                    ->rawColumns(['rest_id', 'item_name', 'name', 'is_mandatory', 'is_multi_select', 'action'])
                    ->setRowId(fn($Records) => 'myDtRow' . $Records->id)
                    ->make(true);

            return $response;
        }

        Flash::error($this->list_permission_error_message);
        return redirect()->route($this->redirect_home);
    }

    public function restaurant_datatable(Request $request) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->list_permission) || $Auth_User->can('all')) {
            $rest_id = $Auth_User->rest_id;
            $Records = AddonType::leftjoin('items', 'addon_types.item_id', '=', 'items.id')
                    ->leftJoin('menus', 'items.menu_id', '=', 'menus.id')
                    ->leftJoin('restaurants', 'menus.rest_id', '=', 'restaurants.id')
                    ->select(['addon_types.id', 'addon_types.title as name', 'addon_types.title_ar as name_ar', 'addon_types.is_mandatory', 'addon_types.is_multi_select', 'addon_types.max_selection', 'items.name as item_name'])
                    ->where('addon_types.status', '=', 1)
                    ->where('restaurants.id', $rest_id);

            $response = Datatables::of($Records)
                    ->filter(function ($query) use ($request) {
                        if ($request->has('item_id') && $request->get('item_id') != -1) {
                            $query->where('addon_types.item_id', '=', $request->get('item_id'));
                        }
                        if ($request->has('name') && !empty($request->name)) {
                            $query->where('addon_types.title', 'like', "%{$request->get('name')}%");
                        }
                        if ($request->has('name_ar') && !empty($request->name_ar)) {
                            $query->where('addon_types.title_ar', 'like', "%{$request->get('name_ar')}%");
                        }
                        if ($request->has('is_mandatory') && $request->get('is_mandatory') != -1) {
                            $query->where('addon_types.is_mandatory', '=', $request->get('is_mandatory'));
                        }
                        if ($request->has('is_multi_select') && $request->get('is_multi_select') != -1) {
                            $query->where('addon_types.is_multi_select', '=', $request->get('is_multi_select'));
                        }
                        if ($request->has('max_selection') && !empty($request->max_selection)) {
                            $query->where('addon_types.max_selection', '>=', $request->get('max_selection'));
                        }
                    })
                    ->addColumn('item_name', fn($Records) => $Records->item_name)
                    ->addColumn('name', function ($Records) use ($Auth_User) {
                        $title = $Records->name;
                        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
                            $title = '<a href="' . route($this->view_route, $Records->id) . '" title="View Details">' . $title . '</a>';
                        }
                        return $title;
                    })
                    ->addColumn('is_mandatory', function ($Records) use ($Auth_User) {
                        return $this->generateStatusLink('addon-types-disableMandatory', 'addon-types-enableMandatory', $Records, $Auth_User);
                    })
                    ->addColumn('is_multi_select', function ($Records) use ($Auth_User) {
                        return $this->generateStatusLink('addon-types-disableMultiselect', 'addon-types-enableMultiselect', $Records, $Auth_User);
                    })
                    ->addColumn('action', function ($Records) use ($Auth_User) {
                        return $this->generateActionButtons($Records, $Auth_User);
                    })
                    ->rawColumns(['item_name', 'name', 'is_mandatory', 'is_multi_select', 'action'])
                    ->setRowId(fn($Records) => 'myDtRow' . $Records->id)
                    ->make(true);

            return $response;
        }

        Flash::error($this->list_permission_error_message);
        return redirect()->route($this->redirect_home);
    }

    private function generateStatusLink($enableRoute, $disableRoute, $Records, $Auth_User) {
        $status = $Records->is_mandatory;
        $str = '';
        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            if ($status == 1) {
                $str = '<a href="' . route($disableRoute, $Records->id) . '" class="btn btn-success btn-sm" title="Disable Mandatory">Yes</a>';
            }
            else {
                $str = '<a href="' . route($enableRoute, $Records->id) . '" class="btn btn-danger btn-sm" title="Enable Mandatory">No</a>';
            }
        }
        else {
            $str = $status == 1 ? 'Yes' : 'No';
        }
        return $str;
    }

    private function generateActionButtons($Records, $Auth_User) {
        $str = "<div class='btn-group' role='group' aria-label='Horizontal Info'>";
        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $str .= '<a class="btn btn-outline-primary" href="' . route($this->view_route, $Records->id) . '" title="View Details"><i class="fa fa-eye"></i></a>';
        }
        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $str .= '<a class="btn btn-outline-primary" href="' . route($this->edit_route, $Records->id) . '" title="Edit"><i class="fa fa-edit"></i></a>';
        }
        if ($Auth_User->can($this->delete_permission) || $Auth_User->can('all')) {
            $str .= '<a href="javascript:void(0);" data-id="' . $Records->id . '" data-url="' . route($this->delete_route, $Records->id) . '" class="btn btn-outline-danger delete_item" title="Delete"><i class="fa fa-trash"></i></a>';
        }
        $str .= "</div>";
        return $str;
    }

    public function relation_count($record_id) {
        return Addon::where('type_id', $record_id)->count();
    }

    public function create() {
        if (Auth::user()->can($this->add_permission) || Auth::user()->can('all')) {
            $Items = $this->get_items_pluck();
            return view($this->views_path . '.create', compact("Items"));
        }

        Flash::error($this->add_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function store(Request $request) {
        if (Auth::user()->can($this->add_permission) || Auth::user()->can('all')) {
            $request->validate([
                'item_id'     => 'required',
                'title'       => 'required',
                'file_upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4048'
            ]);

            $item_id = trim($request->item_id);
            $name = trim($request->title);

            if ($name != '') {
                if (!AddonType::where('item_id', $item_id)->where('title', $name)->exists()) {
                    $Model_Data = new AddonType();
                    $Model_Data->item_id = $item_id;
                    $Model_Data->title = $name;
                    $Model_Data->is_mandatory = $request->is_mandatory;
                    $Model_Data->is_multi_select = $request->is_multi_select;
                    $Model_Data->max_selection = $request->max_selection;

                    $image = '';
                    if ($request->hasFile('file_upload')) {
                        $file_uploaded = $request->file('file_upload');
                        $image = date('YmdHis') . "." . $file_uploaded->getClientOriginalExtension();
                        $uploads_path = $this->uploads_path;

                        if (!is_dir($uploads_path)) {
                            mkdir($uploads_path);
                            copy($this->_UPLOADS_ROOT . "/index.html", $uploads_path . "/index.html");
                        }

                        $file_uploaded->move($uploads_path, $image);
                    }

                    $Model_Data->icon = $image;
                    $Model_Data->created_by = Auth::user()->id;
                    $Model_Data->save();

                    Flash::success($this->msg_created);
                    return redirect()->route('items.edit', $item_id);
                }

                Flash::error($this->msg_exists);
                return redirect()->route('items.edit', $item_id);
            }

            Flash::error($this->msg_required);
            return redirect()->route('items.edit', $item_id);
        }

        Flash::error($this->add_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function show($id) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $Model_Data = AddonType::find($id);
            if (!$Model_Data) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Items = $this->get_items_pluck();
            return view($this->views_path . '.show', compact("Model_Data", "Items"));
        }

        Flash::error($this->view_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function edit($id) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $Model_Data = AddonType::find($id);
            if (!$Model_Data) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Items = $this->get_items_pluck();
            $restaurants_array = Restaurant::where('id', '>=', 1)->where('status', 1)->orderBy('name')->pluck('name', 'id');
            return view($this->views_path . '.edit', compact("Model_Data", "restaurants_array", "Items"));
        }

        Flash::error($this->edit_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function update($id, Request $request) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $request->validate([
                'item_id' => 'required',
                'title'   => 'required',
            ]);

            $Model_Data = AddonType::find($id);
            if (!$Model_Data) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $item_id = trim($request->item_id);
            $name = trim($request->title);

            if ($name != '') {
                $bool = AddonType::where('item_id', $item_id)->where('title', $name)->where('id', '!=', $id)->exists();

                if (!$bool) {
                    $Model_Data->item_id = $item_id;
                    $Model_Data->title = $name;
                    $Model_Data->is_mandatory = $request->is_mandatory;
                    $Model_Data->is_multi_select = $request->is_multi_select;
                    $Model_Data->max_selection = $request->max_selection;

                    $image = $Model_Data->icon;
                    if ($request->hasFile('file_upload')) {
                        $file_uploaded = $request->file('file_upload');
                        $image = date('YmdHis') . "." . $file_uploaded->getClientOriginalExtension();
                        $file_uploaded->move($this->uploads_path, $image);

                        if ($Model_Data->icon) {
                            File::delete($this->uploads_path . "/" . $Model_Data->icon);
                        }
                    }

                    $Model_Data->icon = trim($image);
                    $Model_Data->updated_by = $Auth_User->id;
                    $Model_Data->save();

                    Flash::success($this->msg_updated);
                    return redirect()->route('items.edit', $item_id);
                }

                Flash::error($this->msg_exists);
                return redirect()->route('items.edit', $item_id);
            }

            Flash::error($this->msg_required);
            return redirect()->route('items.edit', $item_id);
        }

        Flash::error($this->edit_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function destroy($id) {
        return redirect()->route($this->home_route);
    }

    public function makeInActive($id) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = AddonType::find($id);
            if (!$Model_Data) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Model_Data->status = 0;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();

            Flash::success('Addon Type InActive successfully.');
            return redirect()->route('items.edit', $Model_Data->item_id);
        }

        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function makeActive($id) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = AddonType::find($id);
            if (!$Model_Data) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Model_Data->status = 1;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();

            Flash::success('Addon Type Active successfully.');
            return redirect()->route('items.edit', $Model_Data->item_id);
        }

        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function disableMandatory($id) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $Model_Data = AddonType::find($id);
            if (!$Model_Data) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Model_Data->is_mandatory = 0;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();

            Flash::success('Addon Type Mandatory disabled successfully.');
            return redirect()->route('items.edit', $Model_Data->item_id);
        }

        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function enableMandatory($id) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $Model_Data = AddonType::find($id);
            if (!$Model_Data) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Model_Data->is_mandatory = 1;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();

            Flash::success('Addon Type Mandatory Enabled successfully.');
            return redirect()->route('items.edit', $Model_Data->item_id);
        }

        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function disableMultiselect($id) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $Model_Data = AddonType::find($id);
            if (!$Model_Data) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Model_Data->is_multi_select = 0;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();

            Flash::success('Addon Type Multiselect Disabled successfully.');
            return redirect()->route('items.edit', $Model_Data->item_id);
        }

        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function enableMultiselect($id) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $Model_Data = AddonType::find($id);
            if (!$Model_Data) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Model_Data->is_multi_select = 1;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();

            Flash::success('Addon Type Multiselect Enabled successfully.');
            return redirect()->route('items.edit', $Model_Data->item_id);
        }

        Flash::error($this->status_permission_error_message);
        return redirect()->route($this->home_route);
    }
}
