<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\MainController as MainController;

use Auth;
use Flash;
use Yajra\DataTables\Facades\DataTables as Datatables;
use App\Models\User;
use App\Models\Module;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends MainController {

    private $uploads_path = "uploads/users/";
    private $views_path = "users";
    private $home_route = "users.index";
    private $create_route = "users.create";
    private $edit_route = "users.edit";
    private $view_route = "users.show";
    private $delete_route = "users.destroy";
    private $view_application_route = "users_show_application";
    private $msg_created = "User added successfully.";
    private $msg_updated = "User updated successfully.";
    private $msg_deleted = "User deleted successfully.";
    private $msg_not_found = "User not found. Please try again.";
    private $msg_required = "Please fill all required fields.";
    private $msg_exists = "Record Already Exists with same User name";
    private $permissions_updated = "User Permissions updated successfully.";
    private $list_permission = "users-listing";
    private $add_permission = "users-add";
    private $edit_permission = "users-edit";
    private $view_permission = "users-view";
    private $status_permission = "users-status";
    private $delete_permission = "users-delete";
    private $list_permission_error_message = "Error: You are not authorized to View Listings of Car colors. Please Contact Administrator.";
    private $add_permission_error_message = "Error: You are not authorized to Add Car color. Please Contact Administrator.";
    private $edit_permission_error_message = "Error: You are not authorized to Update Car color. Please Contact Administrator.";
    private $view_permission_error_message = "Error: You are not authorized to View Car color details. Please Contact Administrator.";
    private $status_permission_error_message = "Error: You are not authorized to change status of Car color. Please Contact Administrator.";
    private $delete_permission_error_message = "Error: You are not authorized to Delete Car color. Please Contact Administrator.";
    private $msg_approved = "Application Approved successfully.";
    private $msg_rejected = "Application Rejected successfully.";

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
        
        $filter_array = ['-1'=> 'All', '1' => 'Active', '0' => 'Inactive'];
        $filter_status = -1;
        $list_title = 'Users';
        $recordsExists = 0;
        switch($list_type){
            case 'active':
                $filter_status = 1;
                $recordsExists = User::where('status', $this->_ACTIVE)->exists();
                $list_title = 'Active '.$list_title;
                break;
            
            case 'inactive':
                $filter_status = 0;
                $recordsExists = User::where('status', $this->_IN_ACTIVE)->exists();
                $list_title = 'InActive '.$list_title;
                break;
            
            default:
                $recordsExists = User::exists();
                $list_title = 'All '.$list_title;
                break;            
        }

        $restaurantsArray = Restaurant::where('status', $this->_ACTIVE)
                ->orderBy('name', 'asc')
                ->pluck('name', 'id');

        return view($this->views_path . '.listing', compact("recordsExists", "list_title", "filter_status", "filter_array", "restaurantsArray"));
    }

    public function datatable(Request $request) {
        $authUser = Auth::user();

        if (!$this->userCan($this->list_permission)) {
            Flash::error($this->list_permission_error_message);
            return redirect()->route($this->redirect_home);
        }

        return $authUser->rest_id === 0 ? $this->admin_datatable($request) : $this->restaurant_datatable($request);
    }

    public function admin_datatable(Request $request) {
        return $this->getDatatableResponse($request, User::query()->leftJoin('restaurants', 'users.rest_id', '=', 'restaurants.id')->select($this->getUserSelectFields()));
    }

    public function restaurant_datatable(Request $request) {
        return $this->getDatatableResponse($request, User::query()->leftJoin('restaurants', 'users.rest_id', '=', 'restaurants.id')->select($this->getUserSelectFields())->where('users.rest_id', Auth::user()->rest_id));
    }

    private function getUserSelectFields() {
        return [
            'users.id',
            'users.rest_id',
            'users.company_name',
            'users.name',
            'users.email',
            'users.phone',
            'users.status',
            'users.application_status',
            'users.approval_status',
            'restaurants.name as rest_name'
        ];
    }

    private function getDatatableResponse(Request $request, $query) {
        return Datatables::of($query)
                        ->filter(fn($query) => $this->applyFilters($request, $query))
                        ->addColumn('company_name', fn($record) => ucwords($record->rest_id == 0 ? 'Sufra App' : $record->rest_name))
                        ->addColumn('name', fn($record) => $this->formatNameColumn($record))
                        ->addColumn('status', fn($record) => $this->formatStatusColumn($record))
                        ->addColumn('application_status', fn($record) => $record->application_status == 1 ? $this->formatApplicationStatusColumn($record) : '-')
                        ->addColumn('approval_status', fn($record) => match ($record->approval_status) {
                                    1 => 'Approved',
                                    2 => 'Rejected',
                                    default => 'Pending',
                                })
                        ->addColumn('action', fn($record) => $this->formatActionColumn($record))
                        ->rawColumns(['company_name', 'name', 'status', 'application_status', 'action'])
                        ->setRowId(fn($record) => 'myDtRow' . $record->id)
                        ->make(true);
    }

    private function applyFilters(Request $request, $query) {
        $filters = ['rest_id', 'name', 'email', 'phone', 'status', 'application_status', 'approval_status'];
        $hasFilters = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter) && ($filter !== 'rest_id' || $request->get($filter) != -1) && ($filter !== 'status' || $request->get($filter) != -1)) {
                $hasFilters = true;

                $query->when($filter === 'name' || $filter === 'email' || $filter === 'phone', function ($q) use ($request, $filter) {
                    $q->where("users.$filter", 'like', "%{$request->get($filter)}%");
                })->when(!in_array($filter, ['name', 'email', 'phone']), function ($q) use ($request, $filter) {
                    $q->where("users.$filter", $request->get($filter));
                });
            }
        }
        
        if (!$hasFilters) {
            return $query;
        }

        return $query;
    }

    private function formatNameColumn($record) {
        $title = ucwords($record->name);
        return ($this->userCan($this->view_permission)) ? '<a href="' . route($this->view_route, $record->id) . '" title="View Details">' . $title . '</a>' : $title;
    }

    private function formatStatusColumn($record) {
        $status = $record->status;
        $approvalStatus = $record->approval_status;

        if ($this->userCan($this->status_permission)) {
            return ($approvalStatus == '2' || $approvalStatus == '0') ? '<a class="btn btn-danger btn-sm" title="User Inactive" style="pointer-events: none; cursor: default;">
                <span class="fa fa-power-off"></span>
              </a>' : ($status == 1 ? '<a href="' . route('users-inactive', $record->id) . '" class="btn btn-success btn-sm" title="Make User Inactive">
                    <span class="fa fa-power-off"></span>
                  </a>' : '<a href="' . route('users-active', $record->id) . '" class="btn btn-danger btn-sm" title="Make User Active">
                    <span class="fa fa-power-off"></span>
                  </a>');
        }

        return '<span class="fa fa-power-off"></span>';
    }

    private function formatApplicationStatusColumn($record) {
        return '<div class="btn-group" role="group" aria-label="Horizontal Info">
                <a class="btn btn-outline-primary" href="' . route($this->view_application_route, $record->id) . '" title="View Details">
                    <i class="fa fa-eye"></i>
                </a>
            </div>';
    }

    private function formatActionColumn($record) {
        $buttons = "<div class='btn-group' role='group' aria-label='Horizontal Info'>";
        if ($this->userCan($this->edit_permission)) {
            $buttons .= '<a class="btn btn-outline-primary" href="' . route($this->edit_route, $record->id) . '" title="Edit Details">
                        <i class="fa fa-edit"></i>
                    </a>';
        }
        if ($this->userCan($this->view_permission)) {
            $buttons .= '<a class="btn btn-outline-primary" href="' . route($this->view_route, $record->id) . '" title="View Details">
                        <i class="fa fa-eye"></i>
                    </a>';
        }
        return $buttons . "</div>";
    }

    public function create() {
        if (!$this->userCan($this->add_permission)) {
            Flash::error($this->add_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $roles = Role::select('id', 'name', 'display_to')->orderBy('name', 'ASC')->get();//dd($roles);
        $restaurants = Restaurant::where('status', 1)
                ->where('id', '>=', 1)
                ->orderBy('name', 'asc')
                ->pluck('name', 'id');

        return view($this->views_path . '.create', compact('roles', 'restaurants'));
    }

    public function store(Request $request) {
        $authUser = Auth::user();

        if (!$this->userCan($this->add_permission)) {
            Flash::error($this->add_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $validatedData = $request->validate([
            'name'     => 'nullable|string|max:255',
            'phone'    => 'required|string|max:20|unique:users,phone',
            'email'    => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:8',
            'role'     => 'required|string|exists:roles,name',
        ]);

        $userData = new User();
        $userData->rest_id = get_auth_rest_id($request, $authUser);
        $userData->name = $validatedData['name'];
        $userData->phone = $validatedData['phone'];
        $userData->email = $validatedData['email'];
        $userData->company_name = null;
        $userData->status = 1;
        $userData->application_status = 0;
        $userData->approval_status = 1;
        $userData->created_by = $authUser->id;

        if ($request->filled('password')) {
            $userData->password = Hash::make($validatedData['password']);
        }

        $userData->save();

        $userData->assignRole($validatedData['role']);

        Flash::success($this->msg_created);
        return redirect()->route($this->home_route);
    }

    public function show($userId) {
        if (!$this->userCan($this->view_permission)) {
            Flash::error($this->view_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $user = User::find($userId);

        if (!$user) {
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }

        $companyName = 'Sufra App';
        if ($user->rest_id > 0) {
            $restaurant = Restaurant::find($user->rest_id);
            $companyName = $restaurant ? $restaurant->name : $companyName;
        }

        $roleName = $user->roles()->exists() ? ucwords($user->roles()->first()->name) : '';

        return view($this->views_path . '.show', compact('user', 'companyName', 'roleName'));
    }

    public function show_application($userId) {
        if (!$this->userCan($this->view_permission)) {
            Flash::error($this->view_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $user = User::find($userId);

        if (!$user) {
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }

        return view($this->views_path . '.show_application', compact('user'));
    }

    public function edit($userId) {
        if (!$this->userCan($this->edit_permission)) {
            Flash::error($this->edit_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $user = User::find($userId);

        if (!$user) {
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }

        return view($this->views_path . '.edit', compact('user'));
    }

    public function update(Request $request, $user_id) {
        if (!$this->userCan($this->edit_permission)) {
            Flash::error($this->edit_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $validatedData = $request->validate([
            'name'     => 'nullable|string|max:255',
            'phone'    => 'required|string|max:20|unique:users,phone,' . $request->user_id,
            'email'    => 'required|email|unique:users,email,' . $request->user_id,
            'password' => 'nullable|string|min:8',
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }

        $user->name = $validatedData['name'];
        $user->phone = $validatedData['phone'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        Flash::success($this->msg_updated);
        return redirect()->route($this->home_route);
    }

    public function setTheme($option) {
        $user = Auth::user();

        if (!$user) {
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }

        User::where('id', $user->id)->update(['theme' => $option]);
        Flash::success('You have changed the theme successfully');

        return redirect()->route($this->redirect_home);
    }

    public function setHeader($option) {
        $user = Auth::user();

        if (!$user) {
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }

        User::where('id', $user->id)->update(['header' => $option]);
        Flash::success('You have changed the header display successfully');

        return redirect()->route($this->redirect_home);
    }

    public function setSidebar($option) {
        $user = Auth::user();

        if (!$user) {
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }

        User::where('id', $user->id)->update(['sidebar' => $option]);
        Flash::success('You have changed the sidebar display successfully');

        return redirect()->route($this->redirect_home);
    }

    public function approve($userId) {
        $authUser = Auth::user();

        if (!$this->userCan($this->status_permission)) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $user = User::find($userId);
        if (!$user) {
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }

        User::where('id', $userId)->update([
            'status'          => 1,
            'approval_status' => 1
        ]);

        $user->assignRole('seller');

        $this->handleRestaurant($user, $authUser->id);

        Flash::success($this->msg_approved);
        return redirect()->route($this->home_route);
    }

    public function reject($userId) {
        $authUser = Auth::user();

        if (!$this->userCan($this->status_permission)) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $user = User::find($userId);
        if (!$user) {
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }

        User::where('id', $userId)->update([
            'status'          => 0,
            'approval_status' => 2
        ]);

        if ($user->rest_id > 0) {
            $restaurant = Restaurant::find($user->rest_id);
            $restaurant?->update([
                'status'     => 0,
                'updated_by' => $authUser->id
            ]);
        }

        Flash::error($this->msg_rejected);
        return redirect()->route($this->home_route);
    }

    protected function handleRestaurant($user, $updatedById) {
        if ($user->rest_id == 0) {
            $restaurant = Restaurant::create([
                        'name'               => $user->company_name,
                        'arabic_name'        => $user->company_name,
                        'location'           => $user->address,
                        'lat'                => $user->lat,
                        'lng'                => $user->lng,
                        'phoneno'            => $user->phone,
                        'email'              => $user->email,
                        'website_link'       => $user->website,
                        'description'        => $user->company_name,
                        'arabic_description' => $user->company_name,
                        'status'             => 1,
                        'created_by'         => $user->id
            ]);

            $user->update(['rest_id' => $restaurant->id]);
        }
        else {
            $restaurant = Restaurant::find($user->rest_id);
            $restaurant?->update([
                'status'     => 1,
                'updated_by' => $updatedById
            ]);
        }
    }

    public function changePassword() {
        return view("{$this->views_path}.password");
    }

    public function updatePassword(Request $request) {
        $user = Auth::user();

        if (Hash::check($request->current_password, $user->password)) {
            $user->update(['password' => Hash::make($request->new_password)]);
            return redirect()->route('dashboard')->with('success', 'Password successfully changed');
        }

        return redirect()->route('users.changePassword')->with('error', 'Incorrect Password');
    }

    public function userPermissions($id) {
        $user = User::find($id);

        if (!$user) {
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }

        $modules = Module::orderBy('id', 'asc')->get();
        $permissions = [
            'list'   => [],
            'add'    => [],
            'edit'   => [],
            'view'   => [],
            'status' => [],
            'delete' => []
        ];

        foreach ($modules as $module) {
            $moduleId = $module->id;
            $moduleName = $module->module_name;

            $permissions['list'][$moduleId] = $this->checkPermission($user, $module, $moduleName, 'listing', $module->mod_list);
            $permissions['add'][$moduleId] = $this->checkPermission($user, $module, $moduleName, 'add', $module->mod_add);
            $permissions['edit'][$moduleId] = $this->checkPermission($user, $module, $moduleName, 'edit', $module->mod_edit);
            $permissions['view'][$moduleId] = $this->checkPermission($user, $module, $moduleName, 'view', $module->mod_view);
            $permissions['status'][$moduleId] = $this->checkPermission($user, $module, $moduleName, 'status', $module->mod_status);
            $permissions['delete'][$moduleId] = $this->checkPermission($user, $module, $moduleName, 'delete', $module->mod_delete);
        }

        return view("{$this->views_path}.userPermissions", compact('user', 'modules', 'permissions'));
    }

    protected function checkPermission($user, $module, $moduleName, $action, $moduleFlag) {
        if (!$moduleFlag)
            return 0;

        $permission = createSlug("{$moduleName}-{$action}");
        return $user->hasPermissionTo($permission) ? 1 : 0;
    }

    public function userPermissionsSubmit(Request $request) {
        $user = Role::find($request->user_id);

        if (!$user) {
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }

        $modules = Module::orderBy('id', 'asc')->get();
        foreach ($modules as $index => $module) {
            $this->handlePermission($user, $module, 'listing', $module->mod_list, $request->list_module[$index] ?? 0);
            $this->handlePermission($user, $module, 'add', $module->mod_add, $request->add_module[$index] ?? 0);
            $this->handlePermission($user, $module, 'edit', $module->mod_edit, $request->edit_module[$index] ?? 0);
            $this->handlePermission($user, $module, 'view', $module->mod_view, $request->view_module[$index] ?? 0);
            $this->handlePermission($user, $module, 'status', $module->mod_status, $request->status_module[$index] ?? 0);
            $this->handlePermission($user, $module, 'delete', $module->mod_delete, $request->delete_module[$index] ?? 0);
        }

        return redirect()->route('userPermissions', $user->id)->with('message', 'Permissions Assigned Successfully');
    }

    protected function handlePermission($user, $module, $action, $modulePermissionFlag, $requestPermissionFlag) {
        $permission = createSlug("{$module->module_name}-{$action}");

        if ($modulePermissionFlag) {
            if ($requestPermissionFlag && !$user->hasPermissionTo($permission)) {
                $user->givePermissionTo($permission);
            }
            elseif (!$requestPermissionFlag && $user->hasPermissionTo($permission)) {
                $user->revokePermissionTo($permission);
            }
        }
        elseif ($user->hasPermissionTo($permission)) {
            $user->revokePermissionTo($permission);
        }
    }

    public function is_not_authorized($id, $authUser) {
        if ($authUser->rest_id == 0) {
            return false;
        }

        return !Restaurant::where('id', $authUser->rest_id)->exists();
    }

    public function makeInActive($id) {
        $authUser = Auth::user();

        if (!$this->userCan($this->status_permission)) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $user = User::find($id);

        if (is_null($user) || $this->is_not_authorized($id, $authUser)) {
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }

        User::where('id', $id)->update([
            'status'     => 0,
            'updated_by' => $authUser->id,
        ]);

        Flash::success('User deactivated successfully.');
        return redirect()->route($this->home_route);
    }

    public function makeActive($id) {
        $authUser = Auth::user();

        if (!$this->userCan($this->status_permission)) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $user = User::find($id);

        if (is_null($user) || $this->is_not_authorized($id, $authUser)) {
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }

        User::where('id', $id)->update([
            'status'     => 1,
            'updated_by' => $authUser->id,
        ]);

        Flash::success('User activated successfully.');
        return redirect()->route($this->home_route);
    }
}
