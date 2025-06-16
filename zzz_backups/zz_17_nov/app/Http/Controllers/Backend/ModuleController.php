<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\MainController;
use App\Models\Module;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables as Datatables;
use Flash;
use Auth;

class ModuleController extends MainController {

    private $views_path = "modules";
    private $home_route = "modules.index";
    private $create_route = "modules.create";
    private $edit_route = "modules.edit";
    private $view_route = "modules.show";
    private $delete_route = "modules.destroy";
    private $msg_created = "Module added successfully.";
    private $msg_updated = "Module updated successfully.";
    private $msg_deleted = "Module deleted successfully.";
    private $msg_not_found = "Module not found. Please try again.";
    private $msg_required = "Please fill all required fields.";
    private $msg_exists = "Record already exists with the same module name.";
    
    private $permissions = [
        'list' => 'modules-listing',
        'add' => 'modules-add',
        'edit' => 'modules-edit',
        'view' => 'modules-view',
        'status' => 'modules-status',
        'delete' => 'modules-delete'
    ];

    public function index() {
        if ($this->hasPermission('list')) {
            $recordsExists = Module::exists();
            return view($this->views_path . '.listing', compact("recordsExists"));
        }
        return $this->permissionErrorRedirect('list');
    }

    public function datatable(Request $request) {
        if ($this->hasPermission('list')) {
            $records = Module::select(['id', 'module_name', 'mod_list', 'mod_add', 'mod_edit', 'mod_view', 'mod_status', 'mod_delete']);
            
            $response = Datatables::of($records)
                ->filter(fn($query) => $this->applyFilters($query, $request))
                ->addColumn('title', fn($record) => $this->generateLink($record, 'view'))
                ->addColumn('listing', fn($record) => $this->generateStatusButton($record->mod_list))
                ->addColumn('add', fn($record) => $this->generateStatusButton($record->mod_add))
                ->addColumn('edit', fn($record) => $this->generateStatusButton($record->mod_edit))
                ->addColumn('view', fn($record) => $this->generateStatusButton($record->mod_view))
                ->addColumn('status', fn($record) => $this->generateStatusButton($record->mod_status))
                ->addColumn('delete', fn($record) => $this->generateStatusButton($record->mod_delete))
                ->addColumn('action', fn($record) => $this->generateActions($record))
                ->rawColumns(['title', 'listing', 'add', 'edit', 'view', 'status', 'delete', 'action'])
                ->setRowId(fn($record) => 'row-' . $record->id)
                ->make(true);

            return $response;
        }
        return $this->permissionErrorRedirect('list');
    }

    public function create() {
        if ($this->hasPermission('add')) {
            return view($this->views_path . '.create');
        }
        return $this->permissionErrorRedirect('add');
    }

    public function store(Request $request) {
        if ($this->hasPermission('add')) {
            $validated = $request->validate([
                'module_name' => 'required|unique:modules,module_name'
            ]);

            $moduleData = new Module();
            $moduleData->module_name = trim($request->module_name);
            $this->handlePermissions($request, $moduleData);
            $moduleData->created_by = Auth::id();
            $moduleData->save();

            Flash::success($this->msg_created);
            return redirect()->route($this->home_route);
        }
        return $this->permissionErrorRedirect('add');
    }

    public function show($id) {
        if ($this->hasPermission('view')) {
            $module = Module::find($id);
            if ($module) {
                return view($this->views_path . '.show', compact('module'));
            }
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }
        return $this->permissionErrorRedirect('view');
    }

    public function edit($id) {
        if ($this->hasPermission('edit')) {
            $module = Module::find($id);
            if ($module) {
                return view($this->views_path . '.edit', compact('module'));
            }
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }
        return $this->permissionErrorRedirect('edit');
    }

    public function update($id, Request $request) {
        if ($this->hasPermission('edit')) {
            $validated = $request->validate([
                'module_name' => 'required|unique:modules,module_name,' . $id
            ]);

            $module = Module::find($id);
            if ($module) {
                $module->module_name = trim($request->module_name);
                $this->handlePermissions($request, $module);
                $module->save();

                Flash::success($this->msg_updated);
                return redirect()->route($this->home_route);
            }
            Flash::error($this->msg_not_found);
            return redirect()->route($this->home_route);
        }
        return $this->permissionErrorRedirect('edit');
    }

    private function hasPermission($action) {
        $user = Auth::user();
        return $user->can($this->permissions[$action]) || $user->can('all');
    }

    private function permissionErrorRedirect($action) {
        Flash::error("Error: You are not authorized to " . $this->permissions[$action] . ". Please contact Administrator.");
        return redirect()->route($this->home_route);
    }

    private function handlePermissions($request, $module) {
        foreach ($this->permissions as $action => $permission) {
            $permissionName = createSlug($module->module_name . '-' . $action);
            $module->{$action} = $request->has($action) && $request->{$action} == 1 ? 1 : 0;
            if ($module->{$action}) {
                Permission::findOrCreate($permissionName);
            } else {
                Permission::where('name', $permissionName)->delete();
            }
        }
    }

    private function generateLink($record, $action) {
        return $this->hasPermission($action) ? 
            '<a href="' . route($this->view_route, $record->id) . '" title="View Details">' . $record->module_name . '</a>' : 
            $record->module_name;
    }

    private function generateStatusButton($status) {
        return $status == 1 ? 
            '<span class="btn btn-success btn-sm"><i class="fa fa-check"></i></span>' : 
            '<span class="btn btn-danger btn-sm"><i class="fa fa-times"></i></span>';
    }

    private function generateActions($record) {
        $actions = '<div class="btn-group" role="group" aria-label="Actions">';

        if ($this->hasPermission('view')) {
            $actions .= '<a class="btn btn-outline-primary" href="' . route($this->view_route, $record->id) . '" title="View Details">
                            <i class="fa fa-eye"></i>
                          </a>';
        }

        if ($this->hasPermission('edit')) {
            $actions .= '<a class="btn btn-outline-primary" href="' . route($this->edit_route, $record->id) . '" title="Edit Details">
                            <i class="fa fa-edit"></i>
                          </a>';
        }

        $actions .= '</div>';
        return $actions;
    }

    private function applyFilters($query, $request) {
        if ($request->has('title') && !empty($request->title)) {
            $query->where('module_name', 'like', "%{$request->title}%");
        }
    }
}