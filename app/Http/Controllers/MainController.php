<?php

namespace App\Http\Controllers;

use Auth;
use PDF;
use Storage;
use App\Models\AppUser;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderSubDetail;
use App\Models\Restaurant;
use Carbon\Carbon;

class MainController extends Controller {

    protected $_UPLOADS_ROOT = "uploads";
    protected $_UPLOADS_DEFAULT = "uploads/defaults";
    protected $_UPLOADS_APP_USERS = "uploads/app_users";
    protected $_UPLOADS_PEOPLE = "uploads/app_user_people";
    protected $_UPLOADS_USERS = "uploads/users";
    protected $_UPLOADS_AUDIOS = "uploads/audios";
    protected $_UPLOADS_VIDEOS = "uploads/videos";
    protected $_UPLOADS_MENUS = "uploads/menus";
    protected $_UPLOADS_ITEMS = "uploads/items";
    protected $_UPLOADS_RESTAURANTS = "uploads/restaurants";
    protected $_UPLOADS_TABLES = "uploads/tables";
    protected $redirect_home = "dashboard";
    protected $dashboard_route = "dashboard";
    protected $_PAGINATION_START = 1;
    protected $_PAGINATION_LIMIT = 10;
    protected $_TOKEN = NULL;
    protected $_USER = NULL;
    protected $_USER_ID = 0;
    protected $_USER_TYPE_ID = 0;
    protected $_USER_TYPE = '';
    protected $_LATITUDE = NULL;
    protected $_LONGITUDE = NULL;
    protected $_RADIUS = 5;  // Default value for radius
    protected $_ROLE_ADMIN = "admin";
    protected $_ROLE_ADMIN_ID = 1;
    protected $_ROLE_SELLER = "seller";
    protected $_ROLE_SELLER_ID = 2;
    protected $_ROLE_SUPERVISOR = "supervisor";
    protected $_ROLE_SUPERVISOR_ID = 3;
    protected $_ROLE_KITCHEN = "kitchen";
    protected $_ROLE_KITCHEN_ID = 4;
    protected $_IN_ACTIVE = 0;
    protected $_ACTIVE = 1;
    protected $_NOT_AVAILABLE = 0;
    protected $_AVAILABLE = 1;
    protected $_CLOSE = 0;
    protected $_OPEN = 1;
    protected $_BUSY = 2;
    protected $_ORDER_UNKNOWN = 0;
    protected $_ORDER_WAITING = 1;
    protected $_ORDER_CACELLED = 2;
    protected $_ORDER_CONFIRMED = 3;
    protected $_ORDER_DECLINED = 4;
    protected $_ORDER_ACCEPTED = 5;
    protected $_ORDER_PREPARING = 6;
    protected $_ORDER_READY = 7;
    protected $_ORDER_READY_TO_SERVE = 8;
    protected $_ORDER_SERVED = 9;
    protected $_ORDER_NOT_SERVED = 10;
    protected $_ARRAY_ORDER_COMPLETED = [9];
    protected $_ARRAY_ORDER_OPEN = [3, 5, 6, 7, 8];
    protected $_ARRAY_ORDER_CANCELLABLE = [1, 3];
    protected $_ARRAY_ORDER_DECLINEABLE = [3, 5, 6];
    protected $_ARRAY_ORDER_DASHBOARD = [3, 5, 6, 7, 8, 9, 10];
    protected $_ORDER_STATUSES = [
        0  => 'Unknown',
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

    protected function userCan($permission) {
        return Auth::user()->can($permission) || Auth::user()->can('all');
    }

    /* protected function loadView($view, $variables) {
      $data = array_map(fn($var) => $$var, $variables);
      $data = array_combine($variables, $data);

      return view($view, $data);
      } */

    protected function getOrderDetails($order) {
        $order_id = (is_array($order)) ? $order['id'] : $order->id; 
        $Record = commonOrderDetailQuery($order_id, $this->_USER_ID);
        return orders_details($Record, $this->_TOKEN, $this->_LATITUDE, $this->_LONGITUDE);
    }

    protected function get_order_details_common($data) {
        $image = $data->user_photo;
        $image_path = '';
        if ($data->photo_type == 0) {
            $image_path = $image === 'app_user.png' ? 'defaults/' : 'app_users/';
            $image_path .= $image;
            $image_path = uploads($image_path);
        }
        else {
            $image_path .= $image;
        }

        $order = [
            'id'                => (int) $data->id,
            'order_no'          => $data->order_no,
            'rest_id'           => (int) $data->rest_id,
            'rest_name'         => ucwords($data->rest_name) ?? NULL,
            'table_id'          => (int) $data->table_id,
            'table_name'        => ucwords($data->table_name) ?? NULL,
            'table_image'       => uploads($data->table_icon == "table.png" ? 'defaults' : 'serve_tables') . '/' . $data->table_icon,
            'user_id'           => (int) $data->user_id,
            'user_name'         => ucwords($data->user_name) ?? NULL,
            'user_phone'        => $data->user_phone ?? NULL,
            'user_type'         => $data['user_type'],
            'user_type_str'     => $data['user_type'] == 2 ? 'table_user' : 'waiter',
            'user_image'        => $image_path,
            'pin_no'            => $data->pin_no,
            'status'            => (int) $data->status,
            'status_str'        => get_status($data->status),
            'total_value'       => (float) $data->total_value,
            'vat_included'      => (int) $data->vat_included,
            'vat_value'         => (float) $data->vat_value,
            'service_charges'   => (float) $data->service_charges,
            'final_value'       => (float) $data->final_value,
            'lat'               => $data->lat,
            'lng'               => $data->lng,
            'created_date_time' => Carbon::parse($data->created_at)->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
            'created_at'        => strtotime($data->created_at),
            'pickup_option'     => $data->pickup_option,
            'pickup_option_id'  => $data->pickup_option_id,
            'pickup_time'       => strtotime($data->pickup_time),
            'scheduled_time'    => strtotime($data->scheduled_time),
            'pay_status'        => $data->pay_status,
            'pay_method'        => $data->pay_method,
            'decline_reason'    => $data->decline_reason
        ];

        $order['items'] = OrderDetail::where('order_id', $data->id)->get()->map(function ($details) {
                    $addons = OrderSubDetail::where('detail_id', $details->id)->get()->map(function ($sub_details) {
                        return [
                    'id'          => (int) $sub_details->id,
                    'details'     => get_addon_detail_for_order($sub_details->addon_id),
                    'total_value' => (float) $sub_details->total_value
                        ];
                    });
                    return [
                'id'          => (int) $details->id,
                'details'     => get_items_detail_for_order($details->item_id),
                'notes'       => $details->notes,
                'quantity'    => (int) $details->quantity,
                'item_value'  => (float) $details->item_value,
                'discount'    => (float) $details->discount,
                'total_value' => (float) $details->total_value,
                'addons'      => $addons
                    ];
                })->toArray();

        return $order;
    }

    protected function generateInvoice($id) {
        $pdf_data = $this->orders_generate_pdf_data($id);

        if ($pdf_data) {
            $pdf_data['pdf_link'] = url('storage/app/invoices/invoice-' . $id . '.pdf');
            $pdf = PDF::loadView('myPdf', $pdf_data);
            Storage::put('invoices/invoice-' . $id . '.pdf', $pdf->output());
        }
    }

    protected function orders_generate_pdf_data($id) {
        $orders = Order::where('id', $id)->get()->map(fn($data) => $this->get_order_details_common($data));

        if ($orders->isNotEmpty()) {
            $order = $orders->first();
            $user = AppUser::find($order['user_id']);
            $restaurant = Restaurant::find($order['rest_id']);

            $image = $restaurant->profile === "restaurant.png" ? uploads('defaults') . '/restaurant.png' : uploads('restaurants') . '/' . $restaurant->profile;

            $pdf_data = $order;
            $pdf_data['date'] = Carbon::createFromTimestamp($order["created_at"])->format('Y-m-d H:i:s');
            $pdf_data['user_name'] = ucwords($user->name);
            $pdf_data['rest_name'] = ucwords($restaurant->name);
            $pdf_data['rest_image'] = $image;

            return $pdf_data;
        }

        return null;
    }
}
