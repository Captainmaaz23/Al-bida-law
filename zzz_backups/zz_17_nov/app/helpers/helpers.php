<?php

use Illuminate\Http\Request;
use App\Models\{Restaurant, ServeTable, Menu, Items, GeneralSetting};

if (!function_exists('format_time_diff')) {
    function format_time_diff($time_diff, $tokens, $suffix) {
        foreach ($tokens as $unit => $text) {
            if ($time_diff < $unit)
                continue;
            $num_units = floor($time_diff / $unit);
            return "$num_units $text" . ($num_units > 1 ? 's' : '') . " $suffix";
        }
    }
}

if (!function_exists('calExpiryDay')) {
    function calExpiryDay($time, $suffix = "ago") {
        return format_time_diff(time() - $time, [
            31536000 => 'year', 2592000  => 'month', 604800   => 'week',
            86400    => 'day', 3600     => 'hour', 60       => 'minute', 1        => 'second'
                ], $suffix);
    }
}

if (!function_exists('callPickupTime')) {
    function callPickupTime($time, $suffix = "to go") {
        return format_time_diff($time - time(), [
            31536000 => 'year', 2592000  => 'month', 604800   => 'week',
            86400    => 'day', 3600     => 'hour', 60       => 'minute', 1        => 'second'
                ], $suffix);
    }
}

if (!function_exists('convert_ampm')) {
    function convert_ampm($hour) {
        $ampm = $hour >= 12 ? 'PM' : 'AM';
        $hour = $hour % 12 ?: 12;
        return sprintf('%02d:00 %s', $hour, $ampm);
    }
}

if (!function_exists('restaurants_availability_automatically')) {
    function restaurants_availability_automatically() {
        $current_time = time();
        Restaurant::where('is_open', 0)
            ->where('re_open_time', '<>', 0)
            ->where('re_open_time', '<=', $current_time)
            ->update(['is_open' => 1, 're_open_time' => 0]);
    }
}

if (!function_exists('serve_tables_availability_automatically')) {
    function serve_tables_availability_automatically() {
        $current_time = time();
        ServeTable::where('availability', 0)
            ->where('re_available_time', '<>', 0)
            ->where('re_available_time', '<=', $current_time)
            ->update(['availability' => 1, 're_available_time' => 0]);
    }
}

if (!function_exists('menus_availability_automatically')) {
    function menus_availability_automatically() {
        $current_time = time();
        Menu::where('availability', 0)
            ->where('re_available_time', '<>', 0)
            ->where('re_available_time', '<=', $current_time)
            ->update(['availability' => 1, 're_available_time' => 0]);
    }
}

if (!function_exists('items_availability_automatically')) {
    function items_availability_automatically() {
        $current_time = time();
        Items::where('availability', 0)
            ->where('re_available_time', '<>', 0)
            ->where('re_available_time', '<=', $current_time)
            ->update(['availability' => 1, 're_available_time' => 0]);
    }
}

if (!function_exists('get_auth_rest_id')) {
    function get_auth_rest_id(Request $request, $Auth_User) {
        return $Auth_User->rest_id ?: $request->rest_id;
    }
}

if (!function_exists('gen_random')) {
    function gen_random($chars = 11, $type = 0) {
        $char_sets = [
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            '0123456789'
        ];
        $use_chars = $char_sets[$type] ?? $char_sets[0];
        return substr(str_shuffle(str_repeat($use_chars, $chars)), 0, $chars);
    }
}

if (!function_exists('get_decimal')) {
    function get_decimal($number) {
        return number_format((float) round($number, 2), 2, '.', '');
    }
}

if (!function_exists('asset_url')) {
    function asset_url($path, $secure = null) {
        return url('/assets/' . $path, [], $secure);
    }
}

if (!function_exists('uploads')) {
    function uploads($path, $secure = null) {
        return url('/uploads/' . $path, [], $secure);
    }
}

if (!function_exists('createSlug')) {
    function createSlug($str, $delimiter = '-') {
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $slug = preg_replace(['/[^A-Za-z0-9-]+/', '/[\s-]+/'], $delimiter, $slug);
        return strtolower(trim($slug, $delimiter));
    }
}


if (!function_exists('get_table_name')) {
    function get_table_name($id) {
        $table = ServeTable::find($id);
        return $table ? $table->title : $id;
    }
}

if (!function_exists('get_vat_value')) {
    function get_vat_value() {
        return GeneralSetting::find(4)->value ?? 0;
    }
}

if (!function_exists('get_service_fee_value')) {
    function get_service_fee_value() {
        return GeneralSetting::find(5)->value ?? 0;
    }
}

if (!function_exists('get_new_order_notification_audio')) {
    function get_new_order_notification_audio() {
        $setting = GeneralSetting::find(6);
        $file = $setting->value;
        $file_path = ($file === 'new_order.mp3' ? 'defaults/' : 'audios/') . $file;
        return uploads($file_path);
    }
}