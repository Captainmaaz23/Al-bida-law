<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\AppUser;

class AppUserSeeder extends Seeder {
    
    public function run() {        
        $this->generateUsers('table', 1000);        
        $this->generateUsers('waiter', 1000);
    }
    
    private function generateUsers($type, $limit) {
        $users_array = [];
        $prefix = $type == 'waiter' ? 'Waiter' : 'Table';

        for ($i = 1; $i <= $limit; $i++) {
            $users_array[$prefix . ' ' . $i] = strtolower($prefix) . $i;
        }

        $count = 0;
        $phone_number = $type == 'waiter' ? 92345200000 : 92345100000;
        foreach ($users_array as $name => $username) {
            $count++;
            $phone_number++;

            $model = new AppUser();            
            $model->user_type = $type == 'waiter' ? 1 : 2;
            
            if ($type == 'table') {
                $model->table_id = $count;
            }

            $model->name = $name;
            $model->email = $username . '@gmail.com';
            $model->username = $username;
            $model->password = Hash::make($username);
            $model->phone = "+" . $phone_number;
            $model->photo = "app_user.png";
            $model->created_by = "1";

            $model->save();
        }
    }
}
