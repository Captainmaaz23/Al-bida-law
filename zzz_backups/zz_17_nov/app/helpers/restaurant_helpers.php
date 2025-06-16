<?php

use App\Models\Restaurant;
use App\Models\ServeTable;
use App\Models\Menu;
use App\Models\AddonType;
use App\Models\ItemOption;

if (!function_exists('get_items_variants_array')) {

    function get_items_variants_array($menu_item) {
        $item_id = $menu_item->id;
        $has_options = $menu_item->has_options;
        $variants_array = [];

        if ($has_options == 1) {
            $AddonTypes = AddonType::where('item_id', $item_id)->where('status', 1)->get();
            foreach ($AddonTypes as $AddonType) {
                $type_id = $AddonType->id;
                $type_name = $AddonType->title;
                $types_arr = [];

                $ItemOptions = ItemOption::where('item_id', $item_id)->where('type_id', $type_id)->where('status', 1)->get();
                foreach ($ItemOptions as $Option) {
                    $option_arr = [
                        'id'          => $Option->id,
                        'name'        => $Option->name,
                        //'ar_name' => $Option->ar_name,
                        'description' => $Option->description,
                        //'ar_description' => $Option->ar_description,
                        'price'       => $Option->price,
                        'image'       => uploads($Option->picture == "option.png" ? 'defaults' : 'items/options') . '/' . $Option->picture
                    ];
                    $types_arr[] = $option_arr;
                }

                if (count($types_arr) > 0) {
                    $variants_array[] = [
                        'id'      => $type_id,
                        'title'   => $type_name,
                        'options' => $types_arr
                    ];
                }
            }
        }

        return count($variants_array) ? $variants_array : null;
    }

}

if (!function_exists('get_items_array')) {

    function get_items_array($menu_item) {
        $item_value = $menu_item->price;
        $discount = $menu_item->discount;
        $discount_value = $menu_item->discount_type == 0 ? round(($discount / 100) * $item_value, 2) : $discount;

        $total_value = round($item_value - $discount_value, 2);

        return [
            'id'          => $menu_item->id,
            'name'        => $menu_item->name,
            //'ar_name' => $menu_item->ar_name,
            'description' => $menu_item->description,
            //'ar_description' => $menu_item->ar_description,
            'old_price'   => $item_value,
            'price'       => $total_value,
            'image'       => uploads($menu_item->image == "item.png" ? 'defaults' : 'items') . '/' . $menu_item->image,
            'addons'      => get_items_variants_array($menu_item)
        ];
    }

}

if (!function_exists('get_rest_menus_without_items')) {

    function get_rest_menus_without_items($rest_id) {
        $menus = [];

        $Menus = Menu::where('rest_id', $rest_id)->where('status', 1)->orderBy('is_order', 'asc')->get();
        foreach ($Menus as $Menu) {
            $menus[] = [
                'id'    => $Menu->id,
                'name'  => $Menu->title,
                //'ar_name' => $Menu->ar_title,
                'image' => uploads($Menu->icon == "menu.png" ? 'defaults' : 'menus') . '/' . $Menu->icon
            ];
        }

        return count($menus) ? $menus : null;
    }

}

if (!function_exists('get_rest_menus')) {

    function get_rest_menus($rest_id) {
        $menus = [];

        $Menus = Menu::where('rest_id', $rest_id)->where('status', 1)->orderBy('is_order', 'asc')->get();
        foreach ($Menus as $Menu) {
            $items = [];
            foreach ($Menu->items as $menu_item) {
                if ($menu_item->status == 1) {
                    $items[] = get_items_array($menu_item);
                }
            }

            $menus[] = [
                'id'    => $Menu->id,
                'name'  => $Menu->title,
                //'ar_name' => $Menu->ar_title,
                'image' => uploads($Menu->icon == "menu.png" ? 'defaults' : 'menus') . '/' . $Menu->icon,
                'items' => count($items) ? $items : null
            ];
        }

        return count($menus) ? $menus : null;
    }

}

if (!function_exists('get_rest_tables')) {

    function get_rest_tables($rest_id) {
        $tables = [];

        $ServeTables = ServeTable::where('rest_id', $rest_id)->where('status', 1)->orderBy('title', 'asc')->get();//->orderBy('is_order', 'asc')
        foreach ($ServeTables as $Table) {
            $tables[] = [
                'id'    => $Table->id,
                'name'  => $Table->title,
                //'ar_name' => $Table->ar_title,
                'image' => uploads($Table->icon == "table.png" ? 'defaults' : 'serve_tables') . '/' . $Table->icon
            ];
        }

        return count($tables) ? $tables : null;
    }

}

if (!function_exists('is_restaurant_open')) {

    function is_restaurant_open($modelData) {
        return [
            'is_open'      => true,
            'is_busy'      => false,
            'open_str'     => 'Open',
            'closing_time' => ''
        ];
    }

}

if (!function_exists('convert_timestamp_to_text_detailed')) {

    function convert_timestamp_to_text_detailed($time) {
        $tokens = [
            31536000 => 'year', 2592000  => 'month', 604800   => 'week',
            86400    => 'day', 3600     => 'hour', 60       => 'minute', 1        => 'second'
        ];

        foreach ($tokens as $unit => $text) {
            if ($time >= $unit) {
                $numberOfUnits = floor($time / $unit);
                return $numberOfUnits . ' ' . $text . ($numberOfUnits > 1 ? 's' : '');
            }
        }
    }

}

if (!function_exists('common')) {

    function common($modelData, $token = null, $lat = null, $lng = null) {
        return array_merge(
                get_restaurant_core_data($modelData, $token, $lat, $lng),
                [
                    'deals'  => get_rest_deals($modelData->id),
                    'promos' => get_rest_promos($modelData->id)
                ]
        );
    }

}

if (!function_exists('common_home')) {

    function common_home($modelData, $token = null, $lat = null, $lng = null) {
        return $modelData ? get_restaurant_core_data($modelData, $token, $lat, $lng) : null;
    }

}

if (!function_exists('common_user')) {

    function common_user($modelData, $token = null, $lat = null, $lng = null) {
        return [
            'restaurants' => array_merge(
                    get_restaurant_core_data($modelData, $token, $lat, $lng),
                    [
                        'deals'  => get_rest_deals($modelData->id),
                        'promos' => get_rest_promos($modelData->id)
                    ]
            )
        ];
    }

}

if (!function_exists('get_restaurant_core_data')) {

    function get_restaurant_core_data($modelData, $token = null, $lat = null, $lng = null) {
        $id = $modelData->id;
        $open_data = is_restaurant_open($modelData);

        return [
            'id'               => $id,
            'is_like'          => is_liked($token, $id),
            'status'           => $modelData->status == 1,
            'is_open'          => $open_data['is_open'],
            'opening_str'      => $open_data['open_str'],
            'closing_time'     => $open_data['closing_time'],
            'is_featured'      => $modelData->is_featured == 1,
            'basic_details'    => get_basic_details_data($modelData),
            'contact_details'  => get_contact_details_data($modelData),
            'location_details' => get_location_details_data($modelData, $lat, $lng),
            'categories'       => get_rest_categories($id),
            'menus'            => get_rest_menus_without_items($id),
            'slides'           => get_rest_slides($id),
            'reviews'          => get_rest_reviews($id)
        ];
    }

}

if (!function_exists('get_basic_details_data')) {

    function get_basic_details_data($modelData) {
        $open_data = is_restaurant_open($modelData);
        return [
            'name'           => $modelData->name,
            'ar_name'        => $modelData->arabic_name,
            'avg_time'       => $modelData->avg_time . ' mins',
            'is_open'        => $open_data['is_open'],
            'opening_str'    => $open_data['open_str'],
            'closing_time'   => $open_data['closing_time'],
            'status'         => $modelData->status == 1,
            'is_featured'    => $modelData->is_featured == 1,
            'description'    => $modelData->description,
            'ar_description' => $modelData->arabic_description,
            'image'          => uploads($modelData->profile == "restaurant.png" ? 'defaults' : 'restaurants') . '/' . $modelData->profile
        ];
    }

}

if (!function_exists('get_contact_details_data')) {

    function get_contact_details_data($modelData) {
        return [
            'email'   => $modelData->email,
            'phoneno' => $modelData->phoneno,
            'website' => $modelData->website_link
        ];
    }

}

if (!function_exists('get_location_details_data')) {

    function get_location_details_data($modelData, $lat = null, $lng = null) {
        $location_details = [
            'location' => $modelData->location,
            'lat'      => $modelData->lat,
            'lng'      => $modelData->lng,
        ];

        if ($lat !== null && $lng !== null) {
            $location_details['distance'] = near_by_restaurants($lat, $lng, $modelData->id);
        }

        return $location_details;
    }

}

if (!function_exists('categories_data')) {

    function categories_data($records, $restaurant = true, $token = null, $lat = null, $lng = null) {
        return array_map(function ($record) use ($restaurant, $token, $lat, $lng) {
            $cat_image_path = 'uploads/' . ($record->c_image == 'category.png' ? 'defaults/' : 'categories/') . $record->c_image;

            $data = [
                'id'         => $record->c_id,
                'title'      => $record->c_title,
                'ar_title'   => $record->ca_title,
                'image'      => $cat_image_path,
                'restaurant' => $restaurant ? get_restaurants_by_category($record->c_id, $token, $lat, $lng) : null,
            ];

            return $data;
        }, $records);
    }

    function get_restaurants_by_category($categoryId, $token, $lat, $lng) {
        $restRecords = RestaurantCategory::join('restaurants', 'restaurant_categories.rest_id', '=', 'restaurants.id')
                ->where([
                    'restaurant_categories.cat_id' => $categoryId,
                    'restaurant_categories.status' => 1,
                    'restaurants.status'           => 1
                ])
                ->pluck('restaurant_categories.rest_id');

        return array_map(function ($rest_id) use ($token, $lat, $lng) {
            $modelData = Restaurant::find($rest_id);
            return common_home($modelData, $token, $lat, $lng);
        }, $restRecords->all());
    }

}





if (!function_exists('featured_data')) {

    function featured_data($records, $token = null, $lat = null, $lng = null) {
        return array_map(function ($record) use ($token, $lat, $lng) {
            $modelData = Restaurant::find($record->id);
            return ['restaurant' => common_home($modelData, $token, $lat, $lng)];
        }, $records);
    }

}
