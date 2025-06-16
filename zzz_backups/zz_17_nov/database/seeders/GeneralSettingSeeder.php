<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GeneralSetting;

class GeneralSettingSeeder extends Seeder {
    
    public function run() {
        $settings = [
            ['title' => 'Phone Number', 'value' => '+97784567832'],
            ['title' => 'Email', 'value' => 'info@logic-valley.com'],
            ['title' => 'Website', 'value' => 'https://www.logic-valley.com'],
            ['title' => 'GST', 'value' => '5'],
            ['title' => 'Service Charges', 'value' => '0'],
            ['title' => 'New Order Notification Audio', 'value' => 'new_order.mp3'],
            ['title' => 'User Arrived Notification Audio', 'value' => 'user_arrived.mp3']
        ];
        
        foreach ($settings as $setting) {
            GeneralSetting::create([
                'title' => $setting['title'],
                'value' => $setting['value'],
                'created_by' => 1
            ]);
        }
    }
}
