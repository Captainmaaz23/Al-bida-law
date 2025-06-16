<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactDetail;

class ContactDetailSeeder extends Seeder {
    
    public function run() {
        $contactDetails = [
            ['title' => 'Phone Number', 'value' => '+923451234567'],
            ['title' => 'Email', 'value' => 'info@logic-valley.com'],
            ['title' => 'Website', 'value' => 'https://www.logic-valley.com'],
            ['title' => 'Sales Tax', 'value' => '17'],
            ['title' => 'Order Declining Time', 'value' => '3'],
            ['title' => 'Order Declining Reason', 'value' => 'Busy'],
            ['title' => 'Order Collection Time', 'value' => '30'],
            ['title' => 'Ramadan', 'value' => '0'],
            ['title' => 'Maximum Fixed Discount Value', 'value' => '80'],
            ['title' => 'Maximum Percentage Discount Value', 'value' => '80'],
            ['title' => 'New Order Notification Audio', 'value' => 'new_order.mp3'],
        ];

        foreach ($contactDetails as $detail) {
            $model = new ContactDetail();
            $model->title = $detail['title'];
            $model->value = $detail['value'];
            $model->created_by = "1";
            $model->save();
        }
    }
}
