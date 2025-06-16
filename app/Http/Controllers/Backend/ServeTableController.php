<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\MainController as MainController;
use Auth;
use File;
use Flash;
use Yajra\DataTables\Facades\DataTables as Datatables;
use App\Models\ServeTable;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class ServeTableController extends MainController {

    private $uploads_rest = "uploads/restaurants";
    private $uploads_path = "uploads/serve_tables";
    private $msg_created = "Table added successfully.";
    private $msg_updated = "Table updated successfully.";
    private $msg_deleted = "Table deleted successfully.";
    private $msg_not_found = "Table not found. Please try again.";
    private $msg_required = "Please fill all required fields.";
    private $msg_exists = "Record Already Exists with same Table name";
    private $views_path = "restaurants.serve_tables";
    private $home_route = "serve-tables.index";
    private $create_route = "serve-tables.create";
    private $edit_route = "serve-tables.edit";
    private $view_route = "serve-tables.show";
    private $delete_route = "serve-tables.destroy";
    private $list_permission = "serve-tables-listing";
    private $add_permission = "serve-tables-add";
    private $edit_permission = "serve-tables-edit";
    private $view_permission = "serve-tables-view";
    private $status_permission = "serve-tables-status";
    private $delete_permission = "serve-tables-delete";
    private $list_permission_error_message = "Error: You are not authorized to View Listings of Restaurant tables. Please Contact Administrator.";
    private $add_permission_error_message = "Error: You are not authorized to Add Restaurant tables. Please Contact Administrator.";
    private $edit_permission_error_message = "Error: You are not authorized to Update Restaurant tables. Please Contact Administrator.";
    private $view_permission_error_message = "Error: You are not authorized to View Restaurant tables details. Please Contact Administrator.";
    private $status_permission_error_message = "Error: You are not authorized to change status of Restaurant tables. Please Contact Administrator.";
    private $delete_permission_error_message = "Error: You are not authorized to Delete Restaurant tables. Please Contact Administrator.";

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
        serve_tables_availability_automatically();

        $Auth_User = Auth::user();
        
        $filter_array = ['-1'=> 'All', '1' => 'Active', '0' => 'Inactive'];
        $filter_status = -1;
        $list_title = 'Tables';
        $recordsExists = 0;
        switch($list_type){
            case 'active':
                $filter_status = 1;
                $recordsExists = ServeTable::where('rest_id', $Auth_User->rest_id)->where('status', $this->_ACTIVE)->exists();
                $list_title = 'Active '.$list_title;
                break;
            
            case 'inactive':
                $filter_status = 0;
                $recordsExists = ServeTable::where('rest_id', $Auth_User->rest_id)->where('status', $this->_IN_ACTIVE)->exists();
                $list_title = 'InActive '.$list_title;
                break;
            
            default:
                $recordsExists = ServeTable::where('rest_id', $Auth_User->rest_id)->exists();
                $list_title = 'All '.$list_title;
                break;            
        }

        $restaurants_array = Restaurant::active()->pluck('name', 'id');

        $serve_tables = $this->getServeTables($Auth_User->rest_id);

        return view($this->views_path . '.listing', compact("recordsExists", "list_title", "filter_status", "filter_array", "restaurants_array", "serve_tables"));
    }

    private function getServeTables($rest_id) {
        if ($rest_id > 0) {
            return ServeTable::with('restaurant')
                            ->where('rest_id', $rest_id)
                            ->active()
                            ->orderBy('is_order')
                            ->get();
        }
        return [];
    }

    public function datatable(Request $request) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->list_permission) || $Auth_User->can('all')) {
            return $Auth_User->rest_id > 0 ? $this->restaurant_datatable($request) : $this->admin_datatable($request);
        }

        Flash::error($this->list_permission_error_message);
        return redirect()->route($this->home_route);
    }

    public function admin_datatable(Request $request) {
        $Records = ServeTable::leftJoin('restaurants', 'serve_tables.rest_id', '=', 'restaurants.id')
                ->select([
            'serve_tables.rest_id', 'restaurants.name', 'restaurants.arabic_name',
            'serve_tables.id', 'serve_tables.title', 'serve_tables.ar_title',
            'serve_tables.availability', 'serve_tables.status'
        ]);

        return Datatables::of($Records)
                        ->filter(function ($query) use ($request) {
                            $this->applyFilters($query, $request);
                        })
                        ->addColumn('rest_id', fn($record) => $record->name)
                        ->addColumn('title', fn($record) => $record->title)
                        ->addColumn('arabic_name', fn($record) => $record->arabic_name)
                        ->addColumn('availability', fn($record) => $this->getAvailabilityButton($record))
                        ->addColumn('status', fn($record) => $this->getStatusButton($record))
                        ->addColumn('action', fn($record) => $this->getActionButtons($record))
                        ->rawColumns(['title', 'availability', 'status', 'action'])
                        ->setRowId(fn($record) => 'myDtRow' . $record->id)
                        ->make(true);
    }

    private function applyFilters($query, $request) {
        if ($request->has('rest_id') && $request->get('rest_id') != -1) {
            $query->where('serve_tables.rest_id', $request->get('rest_id'));
        }

        if ($request->has('title') && !empty($request->title)) {
            $query->where('serve_tables.title', 'like', "%{$request->title}%");
        }

        if ($request->has('ar_title') && !empty($request->ar_title)) {
            $query->where('serve_tables.ar_title', 'like', "%{$request->ar_title}%");
        }

        if ($request->has('availability') && $request->get('availability') != -1) {
            $query->where('serve_tables.availability', $request->get('availability'));
        }

        if ($request->has('status') && $request->get('status') != -1) {
            $query->where('serve_tables.status', $request->get('status'));
        }
    }

    private function getAvailabilityButton($record) {
        $route = $record->availability == 1 ? 'serve-table-not-available' : 'serve-table-available';
        $btnClass = $record->availability == 1 ? 'btn-success' : 'btn-danger';
        $btnLabel = $record->availability == 1 ? 'Make Table Not Available' : 'Make Table Available';
        $iconClass = $record->availability == 1 ? 'fa-power-off' : 'fa-power-off';

        return "<a class='btn {$btnClass}' href='" . route($route, $record->id) . "' title='{$btnLabel}'>
                <i class='fa {$iconClass}'></i> {$btnLabel}
            </a>";
    }

    private function getStatusButton($record) {
        $route = $record->status == 1 ? 'serve-table-inactive' : 'serve-table-active';
        $btnClass = $record->status == 1 ? 'btn-success' : 'btn-danger';
        $btnLabel = $record->status == 1 ? 'Make Table Inactive' : 'Make Table Active';
        $iconClass = $record->status == 1 ? 'fa-power-off' : 'fa-power-off';

        return "<a class='btn {$btnClass}' href='" . route($route, $record->id) . "' title='{$btnLabel}'>
                <i class='fa {$iconClass}'></i> {$btnLabel}
            </a>";
    }

    private function getActionButtons($record) {
        $actions = '';
        if (Auth::user()->can($this->view_permission) || Auth::user()->can('all')) {
            $actions .= "<a class='btn btn-outline-primary' href='" . route('serve-table.view', $record->id) . "' title='view details'>
                        <i class='fa fa-eye'></i>
                    </a>";
        }
        if (Auth::user()->can($this->edit_permission) || Auth::user()->can('all')) {
            $actions .= "<a class='btn btn-outline-primary' href='" . route('serve-table.edit', $record->id) . "' title='edit details'>
                        <i class='fa fa-edit'></i>
                    </a>";
        }

        return $actions;
    }

    public function restaurant_datatable(Request $request) {
        $Auth_User = Auth::user();
        $rest_id = $Auth_User->rest_id;

        $Records = ServeTable::select(['serve_tables.id', 'serve_tables.title', 'serve_tables.ar_title', 'serve_tables.availability', 'serve_tables.status'])
                ->where('serve_tables.rest_id', '=', $rest_id);

        $response = Datatables::of($Records)
                ->filter(function ($query) use ($request) {
                    if ($request->has('title') && !empty($request->title)) {
                        $query->where('serve_tables.title', 'like', "%{$request->title}%");
                    }

                    if ($request->has('ar_title') && !empty($request->ar_title)) {
                        $query->where('serve_tables.ar_title', 'like', "%{$request->ar_title}%");
                    }

                    if ($request->has('availability') && $request->get('availability') != -1) {
                        $query->where('serve_tables.availability', $request->get('availability'));
                    }

                    if ($request->has('status') && $request->get('status') != -1) {
                        $query->where('serve_tables.status', $request->get('status'));
                    }
                })
                ->addColumn('title', function ($Records) {
                    return $Records->title;
                })
                ->addColumn('availability', function ($Records) use ($Auth_User) {
                    return $this->generateAvailabilityButton($Records, $Auth_User);
                })
                ->addColumn('status', function ($Records) use ($Auth_User) {
                    return $this->generateStatusButton($Records, $Auth_User);
                })
                ->addColumn('action', function ($Records) use ($Auth_User) {
                    return $this->generateActionButtons($Records, $Auth_User);
                })
                ->rawColumns(['title', 'availability', 'status', 'action'])
                ->setRowId(function ($Records) {
                    return 'myDtRow' . $Records->id;
                })
                ->make(true);

        return $response;
    }

    private function generateAvailabilityButton($Records, $Auth_User) {
        $record_id = $Records->id;
        $availability = $Records->availability;
        $str = '';

        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $str = $availability == 1 ? $this->generateAvailabilityActions($record_id, 'success', 'UnAvailable', $availability) : $this->generateAvailabilityActions($record_id, 'danger', 'Available', $availability);
        }
        else {
            $str = '<span class="fa fa-power-off"></span>';
        }

        return $str;
    }

    private function generateAvailabilityActions($record_id, $btnClass, $text, $availability) {
        $route = $availability == 1 ? route('serve-table-not-available', $record_id) : route('serve-table-available', $record_id);
        return "<div class='dropdown'>
        <a href='#' class='btn btn-{$btnClass} dropdown-toggle' type='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
            <span class='fa fa-power-off'></span> Change
        </a>
        <div class='dropdown-menu'>
            <a class='dropdown-item' href='" . $route . "'>{$text}</a>
        </div>
    </div>";
    }

    private function generateStatusButton($Records, $Auth_User) {
        $record_id = $Records->id;
        $status = $Records->status;
        $str = '';

        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $str = $status == 1 ? $this->generateStatusActions($record_id, 'success', 'Make Table Inactive', $status) : $this->generateStatusActions($record_id, 'danger', 'Make Table Active', $status);
        }
        else {
            $str = '<span class="fa fa-power-off"></span>';
        }

        return $str;
    }

    private function generateStatusActions($record_id, $btnClass, $text, $status) {
        $route = $status == 1 ? route('serve-table-inactive', $record_id) : route('serve-table-active', $record_id);
        return "<a href='" . $route . "' class='btn btn-{$btnClass} btn-sm' title='{$text}'>
        <span class='fa fa-power-off'></span>
    </a>";
    }

    private function generateActionButtons($Records, $Auth_User) {
        $record_id = $Records->id;
        $str = "<div class='btn-group' role='group' aria-label='Horizontal Info'>";

        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $str .= "<a class='btn btn-outline-primary' href='" . route($this->view_route, $record_id) . "' title='View Details'>
            <i class='fa fa-eye'></i>
        </a>";
        }

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $str .= "<a class='btn btn-outline-primary' href='" . route($this->edit_route, $record_id) . "' title='Edit Details'>
            <i class='fa fa-edit'></i>
        </a>";
        }

        $str .= "</div>";

        return $str;
    }

    public function create() {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->add_permission) && !$Auth_User->can('all')) {
            Flash::error($this->add_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $rest_id = $Auth_User->rest_id;
        $restaurants_array = $rest_id < 1 ? Restaurant::where('status', 1)->orderBy('name')->pluck('name', 'id') : [$rest_id => $Auth_User->restaurant->name];

        return view($this->views_path . '.create', compact('restaurants_array'));
    }

    public function store(Request $request) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->add_permission) && !$Auth_User->can('all')) {
            Flash::error($this->add_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $request->validate([
            'rest_id'     => 'required',
            'title'       => 'required',
            'file_upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8048'
        ]);

        $image = '';
        if ($request->hasFile('file_upload')) {
            $file_uploaded = $request->file('file_upload');
            $image = date('YmdHis') . '.' . $file_uploaded->getClientOriginalExtension();
            $this->ensureUploadsDirectoryExists();
            $file_uploaded->move($this->uploads_path, $image);
        }

        $rest_id = get_auth_rest_id($request, $Auth_User);
        ServeTable::create([
                    'rest_id'      => $rest_id,
                    'title'        => $request->title,
                    'availability' => $request->availability,
                    'icon'         => $image,
                    'created_by'   => $Auth_User->id,
        ]);

        Flash::success($this->msg_created);
        return redirect()->route($this->home_route);
    }

    public function is_not_authorized($id, $Auth_User) {
        $bool = 0;
        return $bool;
    }

    public function show($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->view_permission) && !$Auth_User->can('all')) {
            Flash::error($this->view_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = ServeTable::find($id);
        if (!$Model_Data || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }

        $restaurants_array = Restaurant::where('id', $Model_Data->rest_id)
                ->where('status', 1)
                ->orderBy('name')
                ->pluck('name', 'id');

        return view($this->views_path . '.show', compact('Model_Data', 'restaurants_array'));
    }

    private function ensureUploadsDirectoryExists() {
        $uploads_path = $this->uploads_path;

        if (!is_dir($uploads_path)) {
            mkdir($uploads_path, 0755, true);
            $this->copyIndexHtml($uploads_path);
        }
    }

    private function copyIndexHtml($uploads_path) {
        $src_file = $this->_UPLOADS_ROOT . '/index.html';
        $dest_file = $uploads_path . '/index.html';
        if (!file_exists($dest_file)) {
            copy($src_file, $dest_file);
        }
    }

    public function edit($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->edit_permission) && !$Auth_User->can('all')) {
            Flash::error($this->edit_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = ServeTable::find($id);
        if (!$Model_Data || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        $restaurants_array = Restaurant::where('id', $Model_Data->rest_id)
                ->where('status', 1)
                ->orderBy('name')
                ->pluck('name', 'id');

        return view($this->views_path . '.edit', compact('Model_Data', 'restaurants_array'));
    }

    public function update($id, Request $request) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->edit_permission) && !$Auth_User->can('all')) {
            Flash::error($this->edit_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = ServeTable::find($id);
        if (!$Model_Data || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        $Model_Data->title = $request->title;
        $Model_Data->availability = $request->availability;

        $image = $Model_Data->icon;
        if ($request->hasFile('file_upload')) {
            $file_uploaded = $request->file('file_upload');
            $image = date('YmdHis') . '.' . $file_uploaded->getClientOriginalExtension();
            $file_uploaded->move($this->uploads_path, $image);

            if ($Model_Data->icon) {
                File::delete($this->uploads_path . '/' . $Model_Data->icon);
            }
        }

        $Model_Data->icon = $image;
        $Model_Data->updated_by = $Auth_User->id;
        $Model_Data->save();

        Flash::success($this->msg_updated);
        return redirect()->route($this->home_route);
    }

    public function destroy($id) {
        return redirect()->route($this->home_route);
    }

    public function makeInActive($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = ServeTable::find($id);
        if (!$Model_Data || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        $Model_Data->status = 0;
        $Model_Data->updated_by = $Auth_User->id;
        $Model_Data->save();

        Flash::success('Table Deactivated successfully.');
        return redirect(route($this->home_route));
    }

    public function makeNotAvailable($id, $minutes = 0) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = ServeTable::find($id);
        if (!$Model_Data || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        $Model_Data->availability = 0;
        $message = "Table set to Not Available successfully.";
        if ($minutes != 0) {
            $Model_Data->re_available_time = time() + ($minutes * 60);
            $message = "Table set to Not Available for $minutes minutes successfully.";
        }
        else {
            $Model_Data->re_available_time = 0;
        }

        $Model_Data->updated_by = $Auth_User->id;
        $Model_Data->save();

        Flash::success($message);
        return redirect(route($this->home_route));
    }

    public function makeActive($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = ServeTable::find($id);
        if (!$Model_Data || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        $Model_Data->status = 1;
        $Model_Data->updated_by = $Auth_User->id;
        $Model_Data->save();

        Flash::success('Table Activated successfully.');
        return redirect(route($this->home_route));
    }

    private function checkPermission($permission, $errorMessage) {
        $Auth_User = Auth::user();
        if (!$Auth_User->can($permission) && !$Auth_User->can('all')) {
            Flash::error($errorMessage);
            return redirect()->route($this->home_route);
        }
        return $Auth_User;
    }

    private function findModel($id) {
        $Model_Data = ServeTable::find($id);
        if (!$Model_Data || $this->isNotAuthorized($id, Auth::user())) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }
        return $Model_Data;
    }

    private function isNotAuthorized($id, $Auth_User) {
        if ($Auth_User->rest_id == 0) {
            return true;
        }

        $rest_id = $Auth_User->rest_id;
        return !ServeTable::where('id', $id)->where('rest_id', $rest_id)->exists();
    }

    private function updateAvailability($id, $availability, $message) {
        $Auth_User = $this->checkPermission($this->status_permission, $this->status_permission_error_message);
        if ($Auth_User instanceof RedirectResponse)
            return $Auth_User;

        $Model_Data = $this->findModel($id);
        if ($Model_Data instanceof RedirectResponse)
            return $Model_Data;

        $Model_Data->availability = $availability;
        $Model_Data->re_available_time = 0;
        $Model_Data->updated_by = $Auth_User->id;
        $Model_Data->save();

        Flash::success($message);
        return redirect(route($this->home_route));
    }

    public function makeAvailable($id) {
        return $this->updateAvailability($id, 1, 'Table set to Available successfully.');
    }
}
