<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use Spatie\Permission\Models\Permission;

class ModuleSeeder extends Seeder {
    
    public function run() {
        $modules = [
            0 => ['Restaurants', 'Menus', 'Items', 'Orders'],
            1 => ['App Users'],
            2 => ['Users', 'Modules', 'Roles'],
            3 => ['General Settings']
        ];
        foreach ($modules as $type => $moduleNames) {
            foreach ($moduleNames as $name) {
                $this->common($name, $type);
            }
        }
    }

    public function common($name, $type) {
        $module = new Module();
        $module->module_name = $name;
        $module->type = $type;
        $module->mod_list = 1;
        $module->mod_add = 1;
        $module->mod_edit = 1;
        $module->mod_view = 1;
        $module->mod_status = 1;
        $module->mod_delete = 1;
        $module->created_by = 1;
        $module->save();

        $permissions = ['listing', 'add', 'edit', 'view', 'status', 'delete'];

        foreach ($permissions as $action) {
            $permissionSlug = createSlug($name . '-' . $action);
            Permission::findOrCreate($permissionSlug);
        }
    }
}
