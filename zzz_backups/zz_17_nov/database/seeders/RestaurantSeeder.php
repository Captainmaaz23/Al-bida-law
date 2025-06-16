<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;

class RestaurantSeeder extends Seeder {
    
    public function run() {
        $restaurants = [
            [
                'name'        => 'Chaaye khana',
                'location'    => 'Rawalpindi',
                'phone'       => 923330000000,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi tristique vulputate ullamcorper.',
                'created_by'  => 1,
            ]/*,
            
            [
                'name'        => 'Monal',
                'location'    => 'Rawalpindi',
                'phone'       => 923340000000,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi tristique vulputate ullamcorper.',
                'created_by'  => 1,
            ],
            
            [
                'name'        => 'Habibi',
                'location'    => 'Rawalpindi',
                'phone'       => 923350000000,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi tristique vulputate ullamcorper.',
                'created_by'  => 1,
            ],
            
            [
                'name'        => 'KFC',
                'location'    => 'Rawalpindi',
                'phone'       => 923360000000,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi tristique vulputate ullamcorper.',
                'created_by'  => 1,
            ],
            
            [
                'name'        => 'Hardees',
                'location'    => 'Rawalpindi',
                'phone'       => 923370000000,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi tristique vulputate ullamcorper.',
                'created_by'  => 1,
            ]*/
        ];

        foreach ($restaurants as $restaurant) {
            $model = new Restaurant();
            $model->name = $restaurant['name'];
            $model->location = $restaurant['location'];
            $model->phoneno = "+" . ($restaurant['phone'] + 1);
            $model->email = strtolower(str_replace(' ','',$restaurant['name'])) . "@gmail.com";
            $model->website_link = strtolower("www." . $restaurant['name'] . ".com");
            $model->profile = "restaurant.png";
            $model->lat = "32.9182002122581";
            $model->lng = "73.7201022545449";
            $model->rating = 3;
            $model->is_open = 1;
            $model->is_featured = 1;
            $model->description = $restaurant['description'];
            $model->created_by = $restaurant['created_by'];
            $model->save();
        }
    }
}
