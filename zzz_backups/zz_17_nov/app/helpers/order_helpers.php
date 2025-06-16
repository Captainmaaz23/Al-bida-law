<?php

use App\Models\{
    Items,
    ItemOption,
    Order,
    OrderDetail,
    OrderSubDetail
};
use Carbon\Carbon;

if (!function_exists('order_status_array')) {
    function ORDER_STATUS_ARRAY() {
        return [
            'Unknown'        => 0,
            'Waiting'        => 1,
            'Canceled'       => 2,
            'Confirmed'      => 3,
            'Declined'       => 4,
            'Accepted'       => 5,
            'Preparing'      => 6,
            'Ready'          => 7,
            'Ready to Serve' => 8,
            'Served'         => 9,
            'Not Served'     => 10
        ];
    }
}

if (!function_exists('get_status')) {
    function get_status($status) {
        $statuses = [
            1  => 'Waiting',
            2  => 'Canceled',
            3  => 'Confirmed',
            4  => 'Declined',
            5  => 'Accepted',
            6  => 'Preparing',
            7  => 'Ready',
            8  => 'Ready to Serve',
            9  => 'Served',
            10 => 'Not Served'
        ];
        return $statuses[$status] ?? 'Unknown';
    }
}

if (!function_exists('update_order_status_automatically')) {
    function update_order_status_automatically() {
        $current_time = Carbon::now()->toDateTimeString(); //time();
        Order::where('scheduled_time', '<=', $current_time)
                ->where('status', ORDER_STATUS_ARRAY()['Accepted'])
                ->update(['preparation_time' => $current_time, 'status' => ORDER_STATUS_ARRAY()['Preparing']]);
    }
}

if (!function_exists('get_common_details_html')) {
    function get_common_details_html($order, $time = true) {
        $pickup_time = $order['status'] == ORDER_STATUS_ARRAY()['Accepted'] ? $order['scheduled_time'] : $order['pickup_time'];
        $pickup_duration = ceil(($pickup_time - time()) / 60);
        $pickup_time = callPickupTime($pickup_time);
        $created_at = calExpiryDay(strtotime($order["created_at"]));
        ?>

        <div class="row">
            <div class="col-sm-6">
                <small>Order # <?php echo $order["order_no"]; ?></small><br>
                <small>For <?php echo get_table_name($order["table_id"]); ?></small>
            </div>
            <div class="col-sm-2 txt_right nomodal">
                <a class="order_fullscreen" data-toggle="modal" data-target="#full_screen_modal" ui-toggle-class="bounce" ui-target="#full_screen_animate" title="View Order Details">
                    <i class="fa fa-expand-arrows-alt"></i>
                </a>
            </div>
            <?php if ($time): ?>
                <div class="col-sm-<?php echo $pickup_duration <= 60 ? '3' : '4'; ?> txt_right fl_right" style="<?php echo $pickup_duration <= 60 ? 'margin-left:30px;' : ''; ?>">
                    <?php if ($pickup_duration <= 60): ?>
                        <div data-id="<?php echo $order['id']; ?>" class="order_timer pickup_timer" data-duration="<?php echo $pickup_duration; ?>" data-called="0"></div>
                    <?php else: ?>
                        <small><i class="fa fa-clock"></i> <?php echo $pickup_time; ?></small>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?php echo get_user_div_for_kitchen($order["user_id"], $created_at); ?>
            </div>
        </div>

        <?php foreach ($order["items"] as $item): ?>
            <div class="row">
                <div class="col-sm-12">
                    <span class="w12 fl_left"><b><?php echo $item["quantity"]; ?>x</b></span>
                    <span class="w88 fl_left"><b><?php echo $item["details"]["name"]; ?></b></span>

                    <?php foreach ($item['addons'] as $addon): ?>
                        <span class="w88 fl_right or_addon">
                            <small><?php echo $addon["details"]["name"]; ?></small>
                        </span>
                    <?php endforeach; ?>

                    <?php if (!empty($item['notes'])): ?>
                        <?php
                        $notes = $item['notes'];
                        $notes_sub = strlen($notes) > 55 ? substr($notes, 0, 52) . '...' : $notes;
                        ?>
                        <span class="w88 fl_right or_notes">
                            <small class="nomodal" data-toggle="tooltip" title="<?php echo $notes; ?>">
                                <?php echo $notes_sub; ?>
                            </small>
                            <small class="inmodal hide"><?php echo $notes; ?></small>                               
                        </span>
                    <?php endif; ?>

                    <span class="w88 fl_right"><hr></span>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="row">
            <div class="col-sm-12">
                <h5 class="or_value_s">Order Value: <?php echo $order["total_value"]; ?></h5>
                <h5 class="or_value_s">GST Value: <?php echo $order["vat_value"]; ?></h5>
                <h5 class="or_value_s">Service Charges: <?php echo $order["service_charges"]; ?></h5>
                <h3 class="or_value">Total Value: <?php echo $order["final_value"]; ?></h3>
            </div>
        </div>
        <?php
    }
}

// Update order status automatically
if (!function_exists('update_order_status_automatically')) {
    function update_order_status_automatically() {
        $current_time = Carbon::now()->toDateTimeString(); //time();
        Order::where('scheduled_time', '<=', $current_time)
                ->where('status', ORDER_STATUS_ARRAY()['Accepted'])
                ->update(['preparation_time' => $current_time, 'status' => ORDER_STATUS_ARRAY()['Preparing']]);
    }
}

// Helper to display pickup details in the kitchen view
if (!function_exists('get_pickup_div_for_kitchen')) {
    function get_pickup_div_for_kitchen($pickup_option, $pickup_option_id) {
        if ($pickup_option == 1) {
            $car = AppUserCar::find($pickup_option_id);
            $car_details = [
                'brand'    => get_brand_details($car->brand_id),
                'type'     => get_type_details($car->type_id),
                'color'    => get_color_details($car->color_id),
                'plate_no' => strtoupper($car->plate_no)
            ];
            $image_path = $car_details['type']['image'] ?? '';
            render_car_pickup($car_details, $image_path);
        }
        else {
            $person = AppUserPeople::find($pickup_option_id);
            $image_path = get_person_image_path($person->image);
            render_person_pickup(ucwords($person->name), $person->phone, $image_path);
        }
    }
}

// Helper to get brand details
if (!function_exists('get_brand_details')) {
    function get_brand_details($brand_id) {
        $brand = CarBrand::find($brand_id);
        return [
            'id'    => $brand->id,
            'name'  => $brand->name,
            'image' => uploads(($brand->image === 'brand.png' ? 'defaults/' : 'car_brands/') . $brand->image)
        ];
    }
}

// Helper to get type details
if (!function_exists('get_type_details')) {
    function get_type_details($type_id) {
        $type = CarType::find($type_id);
        return [
            'id'    => $type->id,
            'name'  => $type->name,
            'image' => uploads(($type->image === 'type.png' ? 'defaults/' : 'car_types/') . $type->image)
        ];
    }
}

// Helper to get color details
if (!function_exists('get_color_details')) {
    function get_color_details($color_id) {
        $color = CarColor::find($color_id);
        return ['id' => $color->id, 'name' => $color->name, 'value' => $color->value];
    }
}

// Helper to render car pickup details
if (!function_exists('render_car_pickup')) {
    function render_car_pickup($car_details, $image_path) {
        ?>
        <div class="usy-dtr">
            <a href="#">
                <?php if ($image_path): ?>
                    <img src="<?php echo $image_path; ?>" alt="<?php echo ucwords($car_details['type']['name']); ?>">
                <?php else: ?>
                    <div class="thumb_name">DT</div>
                <?php endif; ?>
            </a>
            <div class="usy-name">
                <h3 title="Pickup Option: DT">DT</h3>
                <span>
                    <small>
                        <span>
                            <?php echo $car_details['plate_no']; ?>,
                            <?php echo ucwords($car_details['brand']['name']); ?>,
                            <?php echo ucwords($car_details['type']['name']); ?>,
                            <?php echo ucwords($car_details['color']['name']); ?>
                        </span>
                    </small>
                </span>
            </div>
        </div>
        <?php
    }
}

// Helper to get person image path
if (!function_exists('get_person_image_path')) {
    function get_person_image_path($image) {
        return uploads(($image === 'people.png' ? 'defaults/' : 'app_user_people/') . $image);
    }
}

// Helper to render person pickup details
if (!function_exists('render_person_pickup')) {
    function render_person_pickup($name, $phone, $image_path) {
        ?>
        <div class="usy-dtr">
            <a href="#">
                <?php if ($image_path): ?>
                    <img src="<?php echo $image_path; ?>" alt="Image">
                <?php else: ?>
                    <div class="thumb_name">WT</div>
                <?php endif; ?>
            </a>
            <div class="usy-name">
                <h3 title="Pickup Option: DT">WT</h3>
                <span>
                    <small>
                        <span><?php echo $name; ?><br>
                            <i class="fa fa-phone"></i> <?php echo $phone; ?>
                        </span>
                    </small>
                </span>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('commonOrderDetailQuery')) {
    function commonOrderDetailQuery($order_id, $user_id, $rest_id = 1) {

        $Record = Order::leftjoin('restaurants', 'orders.rest_id', '=', 'restaurants.id')
                ->leftjoin('serve_tables', 'orders.table_id', '=', 'serve_tables.id')
                ->leftjoin('app_users', 'orders.user_id', '=', 'app_users.id')
                ->where(['orders.id' => $order_id, 'restaurants.status' => 1])//, 'orders.user_id' => $user_id
                ->select(['orders.*', 'restaurants.name as rest_name',
                    'serve_tables.title as table_name', 'serve_tables.icon as table_icon',
                    'app_users.user_type as user_type', 'app_users.name as user_name', 'app_users.photo_type', 'app_users.photo as user_photo'
                ])
                ->first();
        if(!empty($Record)){            
            $Record = $Record->toArray();
        }
        //dd($Record);
        return $Record;
    }
}

if (!function_exists('orders_details')) {
    function orders_details($Record, $token = null, $lat = null, $lng = null, $check_promo = false, $promo_applicable = false) {
        $Record = (array) $Record;
        $order = [
            'id'              => (int) $Record['id'],
            'status_id'       => (int) $Record['status'],
            'status'          => get_status($Record['status']),
            'table_id'        => (int) $Record['table_id'],
            'is_rated'        => (bool) ($Record['is_rated'] == 1),
            'order_no'        => $Record['order_no'],
            'total_value'     => (float) $Record['total_value'],
            'tax_included'    => (int) $Record['vat_included'],
            'tax_value'       => (float) $Record['vat_value'],
            'service_charges' => (float) $Record['service_charges'],
            'final_value'     => (float) $Record['final_value'],
            //'date_time'       => Carbon::parse($Record['created_at'])->timezone(config('app.timezone')),//with timezone
            'date_time'       => Carbon::parse($Record['created_at'])->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
            'items'           => get_order_items($Record['id'])
        ];

        if (isset($Record['rest_name'])) {
            $order['rest_data'] = [
                'id'   => (int) $Record['rest_id'],
                'name' => $Record['rest_name']
            ];
        }

        if (isset($Record['table_name'])) {
            $order['table_data'] = [
                'id'    => (int) $Record['table_id'],
                'name'  => $Record['table_name'],
                'image' => uploads($Record['table_icon'] == "table.png" ? 'defaults' : 'serve_tables') . '/' . $Record['table_icon']
            ];
        }

        if (isset($Record['user_name'])) {
            $user_type_str = $Record['user_type'] == 2 ? 'table_user' : 'waiter';
            $image = $Record['user_photo'];
            $image_path = '';
            if ($Record['photo_type'] == 0) {
                $image_path = $image === 'app_user.png' ? 'defaults/' : 'app_users/';
                $image_path .= $image;
                $image_path = uploads($image_path);
            }
            else {
                $image_path .= $image;
            }
            $order['user_data'] = [
                'id'       => (int) $Record['user_id'],
                'name'     => $Record['user_name'],
                'type'     => $Record['user_type'],
                'type_str' => $user_type_str,
                'image'    => $image_path
            ];
        }

        if ($order['status_id'] === ORDER_STATUS_ARRAY()['Declined']) {
            $order['decline_reason'] = $Record['decline_reason'];
        }
        
        $invoice = '';
        $pdf_file = 0;
        if ($pdf_file == 1 || $Record['status'] == ORDER_STATUS_ARRAY()['Ready to Serve']) {
            $invoice = url("storage/app/invoices/invoice-{$Record['id']}.pdf");
        }
        $order['invoice'] = $invoice;

        return $order;
    }
}

if (!function_exists('get_order_status')) {
    function get_order_status($Record) {
        $Record = (array) $Record;
        $order = [
            'id'              => (int) $Record['id'],
            'status_id'       => (int) $Record['status'],
            'status'          => get_status($Record['status']),
            'table_id'        => (int) $Record['table_id'],
            'order_no'        => $Record['order_no'],
            'pickup_duration' => calculate_pickup_duration($Record)
        ];

        if ($order['status_id'] === ORDER_STATUS_ARRAY()['Declined']) {
            $order['decline_reason'] = $Record['decline_reason'];
        }
        
        $invoice = '';
        $pdf_file = 0;
        if ($pdf_file == 1 || $Record['status'] == ORDER_STATUS_ARRAY()['Ready to Serve']) {
            $invoice = url("storage/app/invoices/invoice-{$Record['id']}.pdf");
        }
        $order['invoice'] = $invoice;

        return $order;
    }
}

if (!function_exists('calculate_pickup_duration')) {
    function calculate_pickup_duration($Record) {
        $pickup_time = $Record['status'] == ORDER_STATUS_ARRAY()['Accepted'] ? strtotime($Record['scheduled_time']) : strtotime($Record['pickup_time']);
        return max(0, ceil(($pickup_time - time()) / 60));
    }
}

if (!function_exists('get_order_items')) {
    function get_order_items($order_id) {
        return OrderDetail::where('order_id', $order_id)->get()->map(function ($details) {
                    return [
                'id'          => (int) $details->id,
                'details'     => get_items_detail_for_order($details->item_id),
                'notes'       => $details->notes,
                'quantity'    => (int) $details->quantity,
                'item_value'  => (float) $details->item_value,
                'discount'    => (float) $details->discount,
                'total_value' => (float) $details->total_value,
                'addons'      => get_addons($details->id)
                    ];
                })->toArray();
    }
}

if (!function_exists('get_addons')) {
    function get_addons($detail_id) {
        return OrderSubDetail::where('detail_id', $detail_id)->get()->map(function ($sub_details) {
                    return [
                'id'          => (int) $sub_details->id,
                'details'     => get_addon_detail_for_order($sub_details->addon_id),
                'total_value' => (float) $sub_details->total_value
                    ];
                })->toArray();
    }
}

if (!function_exists('get_items_detail_for_order')) {
    function get_items_detail_for_order($menu_item_id) {
        $menu_item = Items::where(['id' => $menu_item_id, 'status' => 1])->first();
        if (!$menu_item)
            return null;

        return [
            'id'             => $menu_item->id,
            'name'           => $menu_item->name,
            'ar_name'        => $menu_item->ar_name,
            'description'    => $menu_item->description,
            'ar_description' => $menu_item->ar_description,
            'image'          => uploads($menu_item->image === "item.png" ? 'defaults' : 'items') . '/' . $menu_item->image
        ];
    }
}

if (!function_exists('get_addon_detail_for_order')) {
    function get_addon_detail_for_order($option_id) {
        $item_option = ItemOption::find($option_id);
        if (!$item_option)
            return null;

        return [
            'id'          => (int) $item_option->id,
            'name'        => $item_option->name,
            'description' => $item_option->description,
            'price'       => (float) $item_option->price,
            'image'       => uploads($item_option->image === "option.png" ? 'defaults' : 'items/options') . '/' . $item_option->image
        ];
    }
}

if (!function_exists('get_items_prices_for_orders')) {
    function get_items_prices_for_orders($id) {
        $menu_item = Items::find($id);
        return $menu_item ? [
            'price'         => $menu_item->price,
            'discount_type' => $menu_item->discount_type,
            'discount'      => $menu_item->discount
        ] : null;
    }
}

if (!function_exists('get_addon_prices_for_orders')) {
    function get_addon_prices_for_orders($id) {
        $addon = ItemOption::find($id);
        return $addon ? ['price' => $addon->price] : null;
    }
}

if (!function_exists('get_user_orders')) {
    function get_user_orders($rest_id, $User, $token = null, $lat = null, $lng = null) {
        $Records = Order::where(['rest_id' => $rest_id, 'user_id' => $User->id])->get();
        return !empty($Records) ? orders_data($Records, $token, $lat, $lng) : null;
    }
}

if (!function_exists('get_user_ongoing_orders')) {
    function get_user_ongoing_orders($User, $token = null, $lat = null, $lng = null) {
        $Records = Order::where('user_id', $User->id)->whereBetween('status', [ORDER_STATUS_ARRAY()['Accepted'], ORDER_STATUS_ARRAY()['Ready']])->get();
        return !empty($Records) ? orders_data($Records, $token, $lat, $lng) : null;
    }
}

if (!function_exists('orders_data')) {
    function orders_data($Records, $token = null, $lat = null, $lng = null) {
        return array_map(fn($Record) => orders_details($Record, $token, $lat, $lng), $Records->get()->toArray());
    }
}