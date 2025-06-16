<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    
    public function run() {
        $this->call([
            GeneralSettingSeeder::class,
            ModuleSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            RestaurantSeeder::class,
            ItemSeeder::class,
            AppUserSeeder::class,
        ]);
    }
}
