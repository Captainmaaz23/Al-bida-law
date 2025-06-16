<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Module;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder {

    public function run() {
        // Create 'admin' role
        $role = Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'web',
        ]);

        // Retrieve all modules
        $modules = Module::all();

        // Iterate over each module to assign permissions
        foreach ($modules as $module) {
            $this->assignPermissionsToRole($role, $module);
        }

        ////////////////////////////////////
        // Create 'seller' role
        $role = Role::firstOrCreate([
            'name'       => 'seller',
            'guard_name' => 'web',
        ]);

        // Modules assigned to 'seller' role
        $sellerModules = ['Restaurants', 'Menus', 'Items', 'Orders'];

        // Iterate over seller modules and assign permissions
        foreach ($sellerModules as $moduleName) {
            $modules = Module::where('module_name', '=', $moduleName)->get();

            foreach ($modules as $module) {
                $this->assignPermissionsToRole($role, $module);
            }
        }


        // Create 'supervisor' role
        $role = Role::firstOrCreate([
            'name'       => 'supervisor',
            'guard_name' => 'web',
        ]);

        // Modules assigned to 'kitchen' role
        $kitchenModules = ['Menus', 'Items', 'Orders'];

        // Iterate over kitchen modules and assign permissions
        foreach ($kitchenModules as $moduleName) {
            $modules = Module::where('module_name', '=', $moduleName)->get();

            foreach ($modules as $module) {
                $this->assignPermissionsToRole($role, $module);
            }
        }


        // Create 'kitchen' role
        $role = Role::firstOrCreate([
            'name'       => 'kitchen',
            'guard_name' => 'web',
        ]);

        // Modules assigned to 'kitchen' role
        $kitchenModules = ['Orders'];

        // Iterate over kitchen modules and assign permissions
        foreach ($kitchenModules as $moduleName) {
            $modules = Module::where('module_name', '=', $moduleName)->get();

            foreach ($modules as $module) {
                $this->assignPermissionsToRole($role, $module);
            }
        }
    }

    private function assignPermissionsToRole(Role $role, Module $module) {
        $permissions = [
            'listing' => $module->mod_list,
            'add'     => $module->mod_add,
            'edit'    => $module->mod_edit,
            'view'    => $module->mod_view,
            'status'  => $module->mod_status,
            'delete'  => $module->mod_delete,
        ];

        // Loop through each permission type and handle them
        foreach ($permissions as $action => $enabled) {
            $permission = $this->generatePermissionSlug($module->module_name, $action);

            if ($enabled) {
                // Give permission if not already granted
                if (!$role->hasPermissionTo($permission)) {
                    $role->givePermissionTo($permission);
                }
            }
            else {
                // Revoke permission if granted
                if ($role->hasPermissionTo($permission)) {
                    $role->revokePermissionTo($permission);
                }
            }
        }
    }

    private function generatePermissionSlug($moduleName, $action) {
        return createSlug($moduleName . '-' . $action);
    }
}
