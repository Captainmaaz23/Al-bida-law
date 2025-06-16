<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder {

    public function run() {
        // Create users
        $this->createUser(
            'admin',
            'admin@gmail.com',
            'admin',
            'System User',
            '+923450000000',
            'Sufra',
            1
        );

        // Create restaurants
        $array = array();
        $array['Chaaye khana'] = '+92333100000';
        /*$array['Monal'] = '+92333200000';
        $array['Habibi'] = '+92333400000';
        $array['KFC'] = '+92333300000';
        $array['Hardees'] = '+92333500000';*/
        $rest_id = 0;
        foreach($array as $name => $phone){
            $rest_id++;
            $this->createUser(
                'seller',
                'manager'.$rest_id.'@gmail.com',
                'manager'.$rest_id,
                'Manager '.$name,
                $phone.'1',
                $name,
                $rest_id
            );

            $this->createUser(
                'kitchen',
                'kitchen'.$rest_id.'@gmail.com',
                'kitchen1',
                'Kitchen '.$name,
                $phone.'2',
                $name,
                $rest_id
            );

            $this->createUser(
                'supervisor',
                'supervisor'.$rest_id.'@gmail.com',
                'supervisor'.$rest_id,
                'Supervisor '.$name,
                $phone.'3',
                $name,
                $rest_id
            );
        }
    }

    private function createUser(
            string $role,
            string $email,
            string $password,
            string $name,
            string $phone,
            string $companyName,
            int $rest_id = null
    ) {
        // Create new user
        $user = new User();
        $user->rest_id = $rest_id;
        $user->company_name = $companyName;
        $user->name = $name;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->phone = $phone;
        $user->address = "Rawalpindi";
        $user->status = 1;
        $user->approval_status = 1;
        $user->save();

        // Assign role to user
        $user->assignRole($role);
    }
}
