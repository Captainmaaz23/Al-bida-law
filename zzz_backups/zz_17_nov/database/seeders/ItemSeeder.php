<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServeTable;
use App\Models\Menu;
use App\Models\Items;
use App\Models\AddonType;
use App\Models\ItemOption;

class ItemSeeder extends Seeder {

    public function run() {
        $addonTypes = ["Flavour", "Size", "Toppings", "Drinks"];
        $items = array();
        $menus['Our Specials'] = [
            'Chicken Fajita Wrap'   => 1325,
            'Chicken Parmesan'      => 1995,
            'Moroccan Chicken'      => 1950,
            'Chicken Steak'         => 1550,
            'Chicken Chow Mein'     => 1275,
            'Chicken Chili Dry'     => 1595,
            'Beef Chili Dry'        => 1695,
            'Kung Pao Chicken'      => 1450,
            'Spicy Grilled Chicken' => 1595,
            'Fettuccine Alfredo'    => 1995,
            'Quesadillas'           => 1850,
            'Fish & Chips'          => 2195,
            'Swiss Polo Chicken'    => 1995];
        $menus['Teas/ Coffees'] = [
            'Regular Chaaye'    => 325,
            'Dhood Patti'       => 350,
            'Matka Chaaye'      => 350,
            'Latte'             => 425,
            'Cappuccino'        => 425,
            'Espresso (Single)' => 425,
            'Roasted Tea'       => 425,
            'Espresso (Double)' => 425,
            'Karak Chaaye'      => 425,
            'Coffee with Donut' => 425,
            'Special Chaaye'    => 595,
            'Masala Chaaye'     => 495,
            'Peshawari Kehva'   => 425,
            'Gurr Ki Chaaye'    => 425,
            'Hunza Tea'         => 425,
            'Kashmiri Chaaye'   => 425];
        $menus['Breakfast'] = [
            'Garlic Mushroom on Toast'                      => 995,
            'Croissant with Egg & Cheese'                   => 995,
            'Crepe Stuffed with Scrambled Eggs and Sausage' => 1450,
            'Nutella Crepe'                                 => 1450,
            'Crepes'                                        => 665,
            'Beef Nihari'                                   => 1595,
            'Nutella Stuffed French Toast'                  => 1450,
            'French Toast'                                  => 775,
            'Pancakes'                                      => 775,
            'Khagina'                                       => 925,
            'Waffles'                                       => 975,
            'Waffles with Ice Cream & Chocolate Syrup'      => 1375,
            'Chicken Qeema'                                 => 1150,
            'Beef Qeema'                                    => 1150,
            'Sausage'                                       => 525,
            'Croissant'                                     => 350,
            'Paratha'                                       => 175,
            'Aloo Paratha'                                  => 475,
            'Gurr Paratha'                                  => 595,
            'Naan'                                          => 125,
            'Roti'                                          => 125,
            'Roghni Naan'                                   => 175];
        $menus['Burgers/ Pizzas/ Sandwiches'] = [
            'Crispy Chicken Zinger'             => 1295,
            'Pulled Chicken Burger'             => 1295,
            'Chicken & Jalapeno'                => 1495,
            'Black Olive & Mushroom'            => 1495,
            'Margherita'                        => 1275,
            'Pepperoni'                         => 1495,
            'Grilled Chicken & Cheese Sandwich' => 1295,
            'Spinach & Olives'                  => 1495,
            'Steak Sandwich​'                   => 1395,
            'Hot Dog'                           => 1045,
            'Bun Kabab'                         => 1250,
            'Club Sandwich'                     => 1575,
            'Bright Bite Sandwich'              => 1350];
        $menus['Soup/ Salads'] = [
            'Soup of the Day'       => 795,
            'Caesar\'s Salads'      => 1350,
            'Grilled Chicken Salad' => 1450];
        $menus['Bakery/ Desserts'] = [
            'Skillet Cookie with Ice Cream' => 1265,
            'Apple Pie'                     => 625,
            'Chocolate Molten Cake'         => 1095,
            'Carrot Cake Slice'             => 695,
            'Chocolate Mud Cake'            => 470,
            'Banana Bread'                  => 350,
            'Chocolate Brownie'             => 395,
            'Donut'                         => 225,
            'Chocolate Croissant'           => 395,
            'Jalebi'                        => 450];
        $menus['Beverages'] = [
            'Pina Colada'             => 675,
            'Lemon Fizz'              => 475,
            'Mint Magic'              => 575,
            'Lemonade'                => 475,
            'Oreo Shake'              => 850,
            'Nutella Shake'           => 875,
            'Chocolate Brownie Shake' => 725,
            'Banana Shake'            => 595,
            'Apple Juice'             => 695,
            'Fresh Lime'              => 275,
            'Lassi Sweet'             => 450,
            'Lassi Namkeen'           => 450,
            'Soft Drinks'             => 225,
            'Diet Soft Drinks'        => 275,
            'Mineral Water Large'     => 275,
            'Mineral Water Small'     => 150,
            'Peach Iced Tea'          => 625,
            'Strawberry Iced Tea'     => 625,
            'Mango Iced Tea'          => 625,
            'Peach & Lime Iced Tea'   => 625,
            'Iced Spanish Latte'      => 895,
            'Iced Vanilla Latte'      => 975,
            'Iced Americano'          => 475,
            'Regular Iced Tea'        => 595];
        $menus['Snacks'] = [
            'Kachori with Alu Ki Bhujia' => 750,
            'Nachos'                     => 1350,
            'Chaat Platter'              => 1375,
            'French Fries'               => 695,
            'Chicken Patty'              => 345,
            'Chicken Strips'             => 1375,
            'Assorted Pakoras'           => 850,
            'Spring Rolls'               => 695,
            'Chicken Wings'              => 1425,
            'Potato Samosa'              => 725,
            'Chicken Wrap'               => 925,
            'Cheese Samosa'              => 975,
            'Kebab Roll'                 => 825,
            'Chicken & Jalapeno Samosa'  => 975,
            'Chicken Samosa'             => 975,
            'Shami Kababs'               => 475];
        
        $rest_counts = 1;//5
        for ($i = 1; $i <= $rest_counts; $i++) {
            $rest_id = $i;
            for($t = 1; $t<=1000; $t++){
                ServeTable::create([
                    'title'      => "Table " . $t,
                    'icon'       => 'table.png',
                    'status'     => 1,
                    'rest_id'    => $rest_id,
                    'created_by' => $rest_id
                ]);
            }
            $j = 0;
            foreach ($menus as $menu => $items) {
                $j++;

                $menu = Menu::create([
                    'title'      => $menu,
                    'icon'       => 'menu.png',
                    'status'     => 1,
                    'rest_id'    => $rest_id,
                    'created_by' => $rest_id
                ]);
                $x = 0;
                foreach ($items as $item_name => $price) {
                    $x++;
                    $item = Items::create([
                        'menu_id'     => $menu->id,
                        'name'        => $item_name." ". $rest_id,
                        'price'       => $price,
                        'discount'    => 0,
                        'total_value' => $price,
                        'has_options' => 1,
                        'variations'  => 1,
                        'image'       => 'item.png',
                        'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                        'created_by'  => $rest_id
                    ]);

                    foreach ($addonTypes as $addonType) {
                        AddonType::create([
                            'item_id'    => $item->id,
                            'title'      => $addonType,
                            'created_by' => 1
                        ]);
                    }

                    foreach ($addonTypes as $key => $addonType) {
                        for ($addon_i = 1; $addon_i <= 4; $addon_i++) {
                            ItemOption::create([
                                'item_id'     => $item->id,
                                'type_id'     => $key + 1,
                                'name'        => "$addonType $addon_i of $item_name",
                                'price'       => ($key + 1) . "0.00",
                                'picture'     => 'option.png',
                                'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                                'created_by'  => $rest_id
                            ]);
                        }
                    }
                }
            }
        }
    }
}
