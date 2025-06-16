<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\MainController as MainController;
use App\Models\Module;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables as Datatables;
use Flash;

class RoleController extends MainController {

    private $views_path = "roles";
    private $home_route = "roles.index";
    private $create_route = "roles.create";
    private $edit_route = "roles.edit";
    private $view_route = "roles.show";
    private $delete_route = "roles.destroy";
    private $msg_created = "Role added successfully.";
    private $msg_updated = "Role updated successfully.";
    private $msg_deleted = "Role deleted successfully.";
    private $msg_not_found = "Role not found. Please try again.";
    private $msg_required = "Please fill all required fields.";
    private $msg_exists = "Record Already Exists with same Role name";
    private $permissions_updated = "Role Permissions updated successfully.";
    private $list_permission = "roles-listing";
    private $add_permission = "roles-add";
    private $edit_permission = "roles-edit";
    private $view_permission = "roles-view";
    private $status_permission = "roles-status";
    private $delete_permission = "roles-delete";
    private $list_permission_error_message = "Error: You are not authorized to View Listings of Car colors. Please Contact Administrator.";
    private $add_permission_error_message = "Error: You are not authorized to Add Car color. Please Contact Administrator.";
    private $edit_permission_error_message = "Error: You are not authorized to Update Car color. Please Contact Administrator.";
    private $view_permission_error_message = "Error: You are not authorized to View Car color details. Please Contact Administrator.";
    private $status_permission_error_message = "Error: You are not authorized to change status of Car color. Please Contact Administrator.";
    private $delete_permission_error_message = "Error: You are not authorized to Delete Car color. Please Contact Administrator.";

    public function index() {
        if ($this->userCan($this->list_permission)) {
            $recordsExists = Role::where('id', '>=', 1)->exists() ? 1 : 0;
            return view($this->views_path . '.listing', compact("recordsExists"));
        }
        else {
            Flash::error($this->list_permission_error_message);
            return redirect()->route($this->redirect_home);
        }
    }

    public function datatable(Request $request) {
        if ($this->userCan($this->list_permission)) {
            $Records = Role::select(['roles.id', 'roles.name', 'roles.display_to']);

            $response = Datatables::of($Records)
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->title)) {
                            $query->where('roles.name', 'like', "%{$request->title}%");
                        }
                        if (!empty($request->display) && $request->display != -1) {
                            $query->where('roles.display_to', '=', $request->display);
                        }
                    })
                    ->addColumn('title', fn($Records) => $Records->name)
                    ->addColumn('display_to', function ($Records) {
                        return $Records->display_to == 0 ? "For Restaurant Users Only" : "For Admin Users Only";
                    })
                    ->addColumn('action', function ($Records) {
                        $record_id = $Records->id;
                        $actions = "<div class='btn-group' role='group' aria-label='Horizontal Info'>";

                        if ($this->userCan($this->view_permission)) {
                            $actions .= sprintf('<a class="btn btn-outline-primary" href="%s" title="View Details"><i class="fa fa-eye"></i></a>', route($this->view_route, $record_id));
                        }

                        if ($this->userCan($this->edit_permission)) {
                            $actions .= sprintf('<a class="btn btn-outline-primary" href="%s" title="Edit Details"><i class="fa fa-edit"></i></a>', route($this->edit_route, $record_id));
                        }

                        $actions .= "</div>";
                        return $actions;
                    })
                    ->rawColumns(['action'])
                    ->setRowId(fn($Records) => 'myDtRow' . $Records->id)
                    ->make(true);

            return $response;
        }
        else {
            Flash::error($this->list_permission_error_message);
            return redirect()->route($this->redirect_home);
        }
    }

    public function create() {
        if ($this->userCan($this->add_permission)) {
            return view($this->views_path . '.create');
        }
        else {
            Flash::error($this->add_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function store(Request $request) {
        if ($this->userCan($this->add_permission)) {
            $request->validate([
                'name'       => 'required|unique:roles,name',
                'guard_name' => 'required',
                'display_to' => 'required|in:0,1'
            ]);

            $name = trim($request->name);
            $guard_name = trim($request->guard_name);
            $display_to = $request->display_to;

            $Model_Data = new Role();
            $Model_Data->name = $name;
            $Model_Data->guard_name = $guard_name;
            $Model_Data->display_to = $display_to;
            $Model_Data->save();

            Flash::success($this->msg_created);
            return redirect()->route($this->home_route);
        }
        else {
            Flash::error($this->add_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function show($id) {
        if ($this->userCan($this->view_permission)) {
            $Model_Data = Role::find($id);

            if (empty($Model_Data)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Modules = Module::orderby('id', 'asc')->get();
            $ModulesByType = [
                1 => $Modules->where('type', 1),
                2 => $Modules->where('type', 2),
                3 => $Modules->where('type', 3),
                4 => $Modules->where('type', 4)
            ];

            $permissions = [
                'list'   => [],
                'add'    => [],
                'edit'   => [],
                'view'   => [],
                'status' => [],
                'delete' => []
            ];

            foreach ($Modules as $Module) {
                $this->setModulePermissions($Module, $Model_Data, $permissions);
            }

            return view($this->views_path . '.show', [
                "Model_Data"   => $Model_Data,
                "Modules"      => $Modules,
                "Modules_1"    => $ModulesByType[1],
                "Modules_2"    => $ModulesByType[2],
                "Modules_3"    => $ModulesByType[3],
                "Modules_4"    => $ModulesByType[4],
                "list_array"   => $permissions['list'],
                "add_array"    => $permissions['add'],
                "edit_array"   => $permissions['edit'],
                "view_array"   => $permissions['view'],
                "status_array" => $permissions['status'],
                "delete_array" => $permissions['delete']
            ]);
        }
        else {
            Flash::error($this->view_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    private function setModulePermissions($Module, $role, &$permissions) {
        $moduleId = $Module->id;
        $moduleName = $Module->module_name;

        $actions = [
            'listing' => 'list',
            'add'     => 'add',
            'edit'    => 'edit',
            'view'    => 'view',
            'status'  => 'status',
            'delete'  => 'delete'
        ];

        foreach ($actions as $action => $permKey) {
            $field = 'mod_' . $permKey;
            if ($Module->$field == 1) {
                $permissionSlug = createSlug("{$moduleName}-{$action}");
                $permissions[$permKey][$moduleId] = $role->hasPermissionTo($permissionSlug) ? 1 : 0;
            }
        }
    }

    public function edit($id) {
        if ($this->userCan($this->edit_permission)) {
            $Model_Data = Role::find($id);

            if (empty($Model_Data)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $Modules = Module::orderby('id', 'asc')->get();
            $ModulesByType = [
                1 => $Modules->where('type', 1),
                2 => $Modules->where('type', 2),
                3 => $Modules->where('type', 3),
                4 => $Modules->where('type', 4)
            ];

            $permissions = [
                'list'   => [],
                'add'    => [],
                'edit'   => [],
                'view'   => [],
                'status' => [],
                'delete' => []
            ];

            foreach ($Modules as $Module) {
                $this->setModulePermissions($Module, $Model_Data, $permissions);
            }

            return view($this->views_path . '.edit', [
                "Model_Data"   => $Model_Data,
                "Modules"      => $Modules,
                "Modules_1"    => $ModulesByType[1],
                "Modules_2"    => $ModulesByType[2],
                "Modules_3"    => $ModulesByType[3],
                "Modules_4"    => $ModulesByType[4],
                "list_array"   => $permissions['list'],
                "add_array"    => $permissions['add'],
                "edit_array"   => $permissions['edit'],
                "view_array"   => $permissions['view'],
                "status_array" => $permissions['status'],
                "delete_array" => $permissions['delete']
            ]);
        }
        else {
            Flash::error($this->edit_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function update($id, Request $request) {
        if ($this->userCan($this->edit_permission)) {
            $request->validate(['name' => 'required']);

            $Model_Data = Role::find($id);

            if (!$Model_Data) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $name = trim($request->name);

            if ($name) {
                $existingRole = Role::where('name', $name)->where('id', '!=', $id)->exists();

                if (!$existingRole) {
                    $Model_Data->update([
                        'name'       => $name,
                        'guard_name' => trim($request->guard_name),
                        'display_to' => $request->display_to
                    ]);

                    Flash::success($this->msg_updated);
                    return redirect(route($this->home_route));
                }
                else {
                    Flash::error($this->msg_exists);
                    return redirect()->route($this->edit_route, $id);
                }
            }
            else {
                Flash::error($this->msg_required);
                return redirect()->route($this->edit_route, $id);
            }
        }
        else {
            Flash::error($this->edit_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function permission_update($id, Request $request) {
        if ($this->userCan($this->edit_permission)) {
            $Model_Data = Role::find($id);

            if (!$Model_Data) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $role = $Model_Data;
            $Modules = Module::orderby('id', 'asc')->get();

            foreach ($Modules as $counter => $Module) {
                $module_name = $Module->module_name;

                foreach (['listing', 'add', 'edit', 'view', 'status', 'delete'] as $action) {
                    $permission = createSlug($module_name . '-' . $action);

                    if ($Module->{'mod_' . $action} == 1) {
                        $insert = isset($request->{$action . '_module'}[$counter]) && $request->{$action . '_module'}[$counter] == 1;

                        if ($role->hasPermissionTo($permission)) {
                            if (!$insert) {
                                $role->revokePermissionTo($permission);
                            }
                        }
                        else {
                            if ($insert) {
                                $role->givePermissionTo($permission);
                            }
                        }
                    }
                    elseif ($role->hasPermissionTo($permission)) {
                        $role->revokePermissionTo($permission);
                    }
                }
            }

            Flash::success($this->permissions_updated);
            return redirect(route($this->edit_route, $id));
        }
        else {
            Flash::error($this->edit_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function destroy($id) {
        if ($this->userCan($this->delete_permission)) {
            return redirect(route($this->home_route));
        }
        else {
            Flash::error($this->delete_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }
}
