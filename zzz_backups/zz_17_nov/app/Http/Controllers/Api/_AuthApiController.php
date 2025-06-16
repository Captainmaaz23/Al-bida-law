<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseController;
use App\Models\Order;
use App\Models\AppUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AuthApiController extends BaseController {
    
    private $_RESTAURANT = NULL;

    public function loginEmail(Request $request) {
        $username = $request->input('username', '');
        $password = $request->input('password', '');

        if (empty($username) || empty($password)) {
            return $this->sendError("Missing Parameters");
        }
        
        $User = AppUser::where('username', '=', $username)->first();
        if (!$User || !Hash::check($password, $User->password)) {
            return $this->sendError('Login Failed, Incorrect Credentials Provided!');
        }
        
        $token = gen_random(9, 1) . '-' . gen_random(5, 2);
        
        DB::table('verification_codes')->insert([
            'user_id'      => $User->id,
            'type'         => 'email',
            'sent_to'      => $username,
            'code'         => '1234',
            'verified'     => 1,
            'token'        => $token,
            'expired'      => 1,
            'generated_at' => now(),
            'created_at'   => now(),
            'updated_at'   => now()
        ]);
        
        $User->update([
            'device_name' => $request->device_name ?? NULL,
            'device_id'   => $request->device_id ?? NULL
        ]);
        
        $data = [
            'token' => $token,
            'user'  => get_user_array($User)
        ];
        
        return $this->sendResponse($data, 'User authenticated, successfully!');
    }

    public function index(Request $request, $action = 'listing') {
        if ($action == 'login') {
            $this->_TOKEN = (!empty($request->header('token'))) ? $request->header('token') : $this->_TOKEN;
            $token = $this->_TOKEN;
            if (!empty($token)) {
                $Verifications = AuthKey::where('auth_key', $token)->first();
                if (!empty($Verifications)) {
                    $this->expire_session($Verifications->user_id);
                }
            }
            
            return $this->loginEmail($request);
        }
        
        $result = $this->base_authentication($request, $action);
        if (!$result['status']) {
            if($result['message_type']){
                return $this->sendSuccess($result['message']);
            } else {
                return $this->sendError($result['message']);
            }
        }
        elseif (!empty($this->_USER)) {
            switch ($action) {
                case 'listing-orders':
                    return $this->orderListingHistory($request);
                    break;

                case 'listing-orders-today':
                    return $this->orderListingToday($request);
                    break;

                case 'listing-orders-open':
                    return $this->orderListingOpen($request);
                    break;

                case 'details-order':
                    return $this->orderDetails($request);
                    break;

                case 'status-order':
                    return $this->orderStatus($request);
                    break;

                case 'cancel-order':
                    return $this->orderCancel($request);
                    break;

                case 'collect-order':
                    return $this->orderCollect($request);
                    break;

                case 'orderCounts':
                    return $this->orderCounts($request);
                    break;

                default:
                    return $this->sendError('Invalid Request');
                    break;
            }
        }
        else {
            return $this->sendError('You are not authorized.');
        }
    }
    
    private function getOrdersQuery($request, $status = NULL, $dateRange = false) {
        $user_id = $this->_USER_ID;
        $page = (int) $this->_PAGINATION_START;
        $limit = (int) $this->_PAGINATION_LIMIT;
        $skip = ($page - 1) * $limit;

        $query = Order::leftjoin('restaurants', 'orders.rest_id', '=', 'restaurants.id')
                ->leftjoin('serve_tables', 'orders.table_id', '=', 'serve_tables.id')
                ->leftjoin('app_users', 'orders.user_id', '=', 'app_users.id')
                ->where(['orders.user_id' => $user_id, 'restaurants.status' => 1]);
        
        $query->when($request->table_id, function ($query) use ($request) {
            if(is_numeric($request->table_id)){
                return $query->where('orders.table_id', $request->table_id);
            } else {
                return $query->whereIn('orders.table_id', explode(',', $request->table_id));
            }
        });

        if ($dateRange) {
            $today = Carbon::today();
            $query->where('orders.created_at', '>=', $today);
        }
        
        if ($status) {
            $query->whereIn('orders.status', $status);
        }
        
        $query->when($request->from_date, function ($query) use ($request) {
            $carbon_from_date = Carbon::parse($request->from_date . ' 00:00:00');
            return $query->where('orders.created_at', '>=', $carbon_from_date);
        });
        $query->when($request->to_date, function ($query) use ($request) {
            $carbon_to_date = Carbon::parse($request->to_date . ' 00:00:00')->addDay();
            return $query->where('orders.created_at', '<', $carbon_to_date);
        });

        $query->select(['orders.*', 'restaurants.name as rest_name',
                    'serve_tables.title as table_name', 'serve_tables.icon as table_icon',
                    'app_users.user_type as user_type', 'app_users.name as user_name', 'app_users.photo_type', 'app_users.photo as user_photo'
                ])
                ->orderBy('orders.id', 'DESC')->skip($skip)->take($limit);
        //$sql = Str::replaceArray('?', $query->getBindings(), $query->toSql());dd($sql);
        return $query;
    }

    private function get_orderListingHistory(Request $request) {
        return $this->getOrdersQuery($request, $this->_ARRAY_ORDER_COMPLETED, false);
    }

    private function get_orderListingOpen(Request $request) {
        return $this->getOrdersQuery($request, $this->_ARRAY_ORDER_OPEN, false);
    }

    private function get_orderListingToday(Request $request) {
        return $this->getOrdersQuery($request, $this->_ARRAY_ORDER_COMPLETED, true);
    }

    public function orderListingHistory(Request $request) {
        $Records = $this->get_orderListingHistory($request);
        $count_all = $Records->count();
        return $this->prepareOrderListingResponse($Records, $count_all, $request);
    }

    public function orderListingOpen(Request $request) {
        $Records = $this->get_orderListingOpen($request);
        $count_all = $Records->count();
        return $this->prepareOrderListingResponse($Records, $count_all, $request);
    }

    public function orderListingToday(Request $request) {
        $Records = $this->get_orderListingToday($request);
        $count_all = $Records->count();
        return $this->prepareOrderListingResponse($Records, $count_all, $request);
    }

    private function prepareOrderListingResponse($Records, $count_all, $request) {
        $orders = NULL;
        $message = 'No Record Found.';

        if ($Records->count() > 0) {
            $array_data = orders_data($Records, $this->_TOKEN, $this->_LATITUDE, $this->_LONGITUDE);
            if (!empty($array_data)) {
                $orders = $array_data;
                $message = 'Orders Listing retrieved successfully.';
            }
        }

        $data = [
            'table_id'    => (int) $request->table_id ?? '',
            'from_date'   => $request->from_date ?? '',
            'to_date'     => $request->to_date ?? '',
            'status'      => $request->status ?? '',
            'page'        => (int) $this->_PAGINATION_START,
            'limit'       => (int) $this->_PAGINATION_LIMIT,
            'page_count'  => (int) $Records->count(),
            'total_count' => (int) $count_all,
            'data'        => $orders,
        ];
        
        return $this->sendResponse($data, $message);
    }

    public function orderDetails(Request $request) {
        $user_id = $this->_USER_ID;
        $order_id = trim($request->order_id);

        $exists = Order::leftjoin('restaurants', 'orders.rest_id', '=', 'restaurants.id')
                ->where(['orders.id' => $order_id, 'orders.user_id' => $user_id, 'restaurants.status' => $this->_ACTIVE])
                ->select(['orders.id'])
                ->first();

        if (!$exists) {
            return $this->sendError('Please provide valid order details.');
        }

        $Record = commonOrderDetailQuery($order_id, $user_id);
        $response_data = orders_details($Record, $this->_TOKEN, $this->_LATITUDE, $this->_LONGITUDE);
        $message = 'Order Details Successfully retrieved';
        
        return $this->response_with_countdata($request, $response_data, $message);
    }

    public function orderStatus(Request $request) {
        $user_id = $this->_USER_ID;
        $order_id = trim($request->order_id);

        $exists = Order::leftjoin('restaurants', 'orders.rest_id', '=', 'restaurants.id')
                ->where(['orders.id' => $order_id, 'orders.user_id' => $user_id, 'restaurants.status' => $this->_ACTIVE])
                ->select(['orders.id'])
                ->first();

        if (!$exists) {
            return $this->sendError('Please provide valid order details.');
        }

        $Record = Order::find($order_id)->toArray();
        $response_data = get_order_status($Record, $this->_TOKEN, $this->_LATITUDE, $this->_LONGITUDE);
        $message = 'Order Status Successfully retrieved';
        
        return $this->response_with_countdata($request, $response_data, $message);
    }

    public function orderCancel(Request $request) {
        $user_id = $this->_USER_ID;
        $id = $request->id ?? NULL;

        if (empty($id)) {
            return $this->sendError('Required parameters are missing!');
        }


        $record = Order::leftJoin('restaurants', 'orders.rest_id', '=', 'restaurants.id')
                ->where(['orders.id' => $id, 'orders.user_id' => $user_id, 'restaurants.status' => $this->_ACTIVE])
                ->select('orders.status')
                ->first();

        if (!$record) {
            return $this->sendError('Record Not Found!');
        }

        $cancel = (in_array($record->status, $this->_ARRAY_ORDER_CANCELLABLE)) ? true : false;

        $message = $cancel ? 'User Order canceled successfully.' : 'Order cannot be canceled.';
        if ($cancel) {
            $order = Order::find($id);
            $order->status = $this->_ORDER_CACELLED;
            $order->cancelled_time = Carbon::now()->toDateTimeString();//time();
            $order->cancel_reason = $request->cancel_reason ?? 'Not Provided';
            $order->save();
        }

        $Record = commonOrderDetailQuery($id, $user_id);
        $response_data = $this->getOrderDetails($Record);
        
        return $this->response_with_countdata($request, $response_data, $message);
    }

    public function orderCollect(Request $request) {
        $user_id = $this->_USER_ID;
        $ids = $request->id ?? NULL;

        if (empty($ids)) {
            return $this->sendError('Required parameters are missing!');
        }

        $order_ids = explode(',', $ids);

        $response_data = NULL;
        $message = 'Order Collected Successfully.';

        foreach ($order_ids as $id) {
            $order = Order::leftJoin('restaurants', 'orders.rest_id', '=', 'restaurants.id')
                    ->where(['orders.id' => $id, 'orders.user_id' => $user_id, 'orders.status' => $this->_ORDER_READY_TO_SERVE, 'restaurants.status' => $this->_ACTIVE])
                    ->first();

            if ($order) {
                $order->collected_time = Carbon::now()->toDateTimeString();//time();
                $order->status = $this->_ORDER_SERVED;
                $order->save();
                $response_data = $this->getOrderDetails($order);
            }
        }
        
        return $this->response_with_countdata($request, $response_data, $message);
    }

    public function orderCounts(Request $request) {
        $id = $request->id ?? NULL;

        if (empty($id)) {
            return $this->sendError('Required parameters are missing!');
        }

        $response_data = [];
        $message = 'Order Counts Successfully.';
        
        return $this->response_with_countdata($request, $response_data, $message);
    }

    private function overall_order_count($request) {
        $all_history_count = $this->get_orderListingHistory($request)->count();
        $open_history_count = $this->get_orderListingOpen($request)->count();
        $today_history_count = $this->get_orderListingToday($request)->count();

        $data = [];
        $data['counts_data'] = [
            'all_history_count'   => $all_history_count,
            'open_history_count'  => $open_history_count,
            'today_history_count' => $today_history_count,
        ];
        return $data;
    }

    private function response_with_countdata(Request $request, $response_data, $message) {
        $response_data = $response_data ?? [];
        
        $counts_data = $this->overall_order_count($request);
        $data = array_merge($response_data, $counts_data);
        
        return $this->sendResponse($data, $message);
    }

    public function get_restaurant_array($record) {
        $rest_img_path = $record->profile == 'restaurant.png' ? 'defaults/' : 'restaurants/';
        $rest_img_path .= $record->profile;

        return [
            'id'             => (int)$record->id,
            'name'           => $record->name,
            'ar_name'        => $record->arabic_name,
            'description'    => $record->description,
            'ar_description' => $record->arabic_description,
            'location'       => $record->location,
            'lat'            => $record->lat,
            'lng'            => $record->lng,
            'image'          => uploads($rest_img_path)
        ];
    }
}
