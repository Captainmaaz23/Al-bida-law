<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseController;
use App\Models\Restaurant;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderSubDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;
//use App\Events\OrderPlaced;

class RestaurantApiController extends BaseController {
    
    private $_RESTAURANT = NULL;

    public function index(Request $request, $action = 'listing') {        
        $result = $this->base_authentication($request, $action);
        if (!$result['status']) {
            if($result['message_type']){
                return $this->sendSuccess($result['message']);
            } else {
                return $this->sendError($result['message']);
            }
        }
        elseif(!empty($this->_USER)) {
            if (isset($request->id)) {
                $id = trim($request->id);
                if ($id) {
                    $modelData = Restaurant::find($id);
                    if (!$modelData || $modelData->status == $this->_IN_ACTIVE) {
                        return $this->sendError('Restaurant Details not found.');
                    }
                    $this->_RESTAURANT = $modelData;
                    switch ($action) {
                        case 'menus':
                            return $this->menus();
                            break;

                        case 'create-order':
                            if ($modelData->is_open == $this->_CLOSE)
                                return $this->sendError('Restaurant is Closed.');
                            
                            if (!isset($request->items))
                                return $this->sendError('Please Provide order details');
                            
                            return $this->orderCreate($request);
                            break;

                        case 'update-order':                            
                            if ($modelData->is_open == $this->_CLOSE)
                                return $this->sendError('Restaurant is Closed.');
                            
                            if (!isset($request->order_id))
                                return $this->sendError('Please Provide order details');
                            
                            if (!isset($request->items))
                                return $this->sendError('Please Provide order details');
                            
                            return $this->orderUpdate($request);
                            break;

                        default:
                            return $this->sendError('Invalid Request');
                            break;
                    }
                }
                else {
                    return $this->sendError('Please Provide Restaurant id in Request');
                }
            }
            elseif ($action == 'order-details' && isset($request->order_id)) {
                $order_id = trim($request->order_id);
                if ($order_id) {
                    $Record = Order::find($order_id)->toArray();
                    if ($Record) {
                        $data = orders_details($Record, $this->_TOKEN, $this->_LATITUDE, $this->_LONGITUDE);
                        return $this->sendResponse($data, 'Order Details Successfully retrieved');
                    }
                    return $this->sendError('Order Not Found. Please try valid order details');
                }
                return $this->sendError('Please provide order details.');
            }

            return $this->sendError('Invalid Request');
        }
        else {
            return $this->sendError('You are not authorized.');
        }
    }
    
    public function menus() {
        $rest_id = $this->_RESTAURANT->id;
        $rest_menus = get_rest_menus($rest_id);
        $message = $rest_menus ? 'Restaurant Menus Listing retrieved successfully.' : 'No Record Found.';
        $rest_tables = get_rest_tables($rest_id);

        $data = [
            'tables' => $rest_tables,
            'menus'  => $rest_menus,
        ];

        return $this->sendResponse($data, $message);
    }
    
    public function reviews() {
        $rest_id = $this->_RESTAURANT->id;
        $rest_reviews = get_rest_reviews($rest_id);
        $message = $rest_reviews ? 'Restaurant Reviews retrieved successfully.' : 'No Record Found.';

        $data = [
            'id'      => $rest_id,
            'reviews' => $rest_reviews,
        ];

        return $this->sendResponse($data, $message);
    }

    public function orderCreate($request) {
        $rest_id = $this->_RESTAURANT->id;
        $user_id = $this->_USER_ID;
        $has_ongoing_order = $this->checkOngoingOrder($user_id);

        if ($has_ongoing_order == 0) {
            $order = $this->createOrder($request, $rest_id, $user_id);
            $order_id = $order->id;

            $order_no = $this->generateOrderNo($order_id);
            return $this->commonProcessing($request, $order, $order_no, $order_id);
        }
        else {
            return $this->sendError('You have an Ongoing Order. You cannot place another order.');
        }
    }

    public function orderUpdate($request) {
        $rest_id = $this->_RESTAURANT->id;
        $user_id = $this->_USER_ID;
        $req_order_id = $request->order_id;
        
        $order_can_edit = Order::where('id', $req_order_id)
                ->where('user_id', $user_id)
                ->whereIn('status', [$this->_ORDER_CONFIRMED, $this->_ORDER_ACCEPTED, $this->_ORDER_PREPARING])
                ->exists();

        if ($order_can_edit) {            
            $order = $this->createOrder($request, $rest_id, $user_id);

            $order_id = $order->id;
            $order_no = $this->generateOrderNo($order_id);
            
            // Cancel the previous order
            {
                $record = Order::find($req_order_id);
                    $record->status = $this->_ORDER_CACELLED;  
                    $record->cancelled_time = Carbon::now()->toDateTimeString();//time();            
                    $record->cancel_reason = 'Cancelled and new order created. New Order No is '.$order_no;
                $record->save();
            }
            
            return $this->commonProcessing($request, $order, $order_no, $order_id);
        }
        else {
            return $this->sendError('You cannot update this order.');
        }
    }
    
    //////////////////

    private function checkOngoingOrder($user_id) {
        /* {
          $Ongoing_orders = Order::where('user_id',$user_id)->where('status','>=',3)->where('status','<>',4)->where('status','<=',8)->get();
          foreach($Ongoing_orders as $order)
          {
          $has_ongoing_order = 1;
          }
          } */
        return 0;
    }
    
    private function commonProcessing($request, $order, $order_no, $order_id){            
        $order_value = 0;
        $items = $request->items;
        foreach ($items as $item) {
            $order_value += $this->processItem($item, $order_id);
        }
        
        $this->updateOrder($order, $order_no, $order_value);

        $data = $this->getOrderDetails($order);
        return $this->sendResponse($data, 'Order Successfully saved');
        
    }

    private function createOrder($request, $rest_id, $user_id) {
        $order = new Order();
            $order->rest_id = $rest_id;
            $order->table_id = $request->table_id;
            $order->user_id = $user_id;
            $order->lat = $this->_LATITUDE ?? 0;
            $order->lng = $this->_LONGITUDE ?? 0;
            $order->pickup_time = Carbon::now()->addMinutes(10);  // 10 minutes from now
            $order->pay_method = 'cash';
            $order->pay_method_id = 0;
            $order->pay_status = 0;
            $order->transaction_id = 0;
            $order->order_value = 0;
            $order->promo_id = 0;
            $order->promo_value = 0;
            $order->total_value = 0;
            $order->vat_included = 0;
            $order->vat_value = 0;
            $order->final_value = 0;
            $order->status = $this->_ORDER_WAITING;
        $order->save();
        return $order;
    }

    private function generateOrderNo($order_id) {
        return sprintf("%02d%02d%02d-%04u", date('y'), date('m'), date('d'), $order_id);
        /*$day = sprintf('%02d', date('d', time()));
        $month = sprintf('%02d', date('m', time()));
        $year = date('y', time());
        $code = sprintf("%04u", $order_id);
        return $year . $month . $day . "-" . $code;*/
    }

    private function processItem($item, $order_id) {
        $notes = $item['notes'] ?? '';
        $item_id = $item['item_id'];
        $quantity = (float) $item['quantity'];
        $item_value = 0;
        $discount_value = 0;
        $total_value = 0;

        $item_details = get_items_prices_for_orders($item_id);
        if ($item_details) {
            $item_value = (float) $item_details['price'];
            $discount_value = $this->calculateDiscount($item_details, $item_value);
            $total_value = ($item_value - $discount_value);
        }

        $total_value *= $quantity;

        $details_id = $this->saveOrderDetail($order_id, $item, $notes, $quantity, $item_value, $discount_value, $total_value);
        $total_value = $this->processAddons($item, $details_id, $total_value);

        return round($total_value, 2);
    }
    
    private function processAddons($item, $details_id, $total_value){
        if (isset($item['addons'])) {
            $quantity = (float) $item['quantity'];
            foreach ($item['addons'] as $addon) {
                $addon_id = $addon['id'];
                $addon_details = get_addon_prices_for_orders($addon_id);
                $addon_value = $addon_details ? $addon_details['price'] * $quantity : 0;

                $sub_details = new OrderSubDetail();
                    $sub_details->detail_id = $details_id;
                    $sub_details->addon_id = $addon_id;
                    $sub_details->total_value = $addon_value;
                $sub_details->save();

                $total_value += $addon_value;
            }
        }
        return $total_value;
    }

    private function calculateDiscount($item_details, $item_value) {
        $discount = (float) $item_details['discount'];
        return ($item_details['discount_type'] == 0) ? round(($discount / 100) * $item_value, 2) : $discount;
    }

    private function saveOrderDetail($order_id, $item, $notes, $quantity, $item_value, $discount_value, $total_value) {
        $details = new OrderDetail();
            $details->order_id = $order_id;
            $details->item_id = $item['item_id'];
            $details->notes = $notes;
            $details->quantity = $quantity;
            $details->item_value = $item_value;
            $details->discount = $discount_value;
            $details->total_value = $total_value;
        $details->save();
        
        return $details->id;
    }

    private function calculateVat($total_order_value) {
        $vat_include = get_vat_value();
        return ($vat_include > 0) ? round(($vat_include / 100) * $total_order_value, 2) : 0;
    }

    private function calculateServiceCharges($total_order_value) {
        $service_charges_include = get_service_fee_value();
        return ($service_charges_include > 0) ? round(($service_charges_include / 100) * $total_order_value, 2) : 0;
    }

    private function updateOrder($order, $order_no, $order_value) {
        $total_order_value = $order_value;
        $vat_value = $this->calculateVat($total_order_value);
        $final_value = $total_order_value + $vat_value;
        $service_charges = $this->calculateServiceCharges($total_order_value);

        $final_value = $final_value + $service_charges;
        
        $trigger_event = 0;
        $order->order_no = $order_no;        
        $order->order_value = $order_value;
        $order->promo_id = (int)($promo_id ?? NULL);
        $order->promo_value = (float) ($promo_value ?? NULL);
        $order->total_value = (float) ($total_order_value ?? NULL);
        $order->vat_value = (float) ($vat_value ?? NULL);
        $order->vat_included = ($order->vat_value > 0) ? 1 : 0;
        $order->service_charges = (float) ($service_charges ?? NULL);
        $order->final_value = (float) ($final_value ?? NULL);

        // Finalize and confirm order if cash method
        if ($order->pay_method == 'cash') {
            $order->status = $this->_ORDER_CONFIRMED;  // Mark as confirmed
            $order->confirmed_time = Carbon::now()->toDateTimeString();//time();
            $trigger_event = 1;
        }
        
        $order->save();
        if($trigger_event){
            //broadcast(new OrderPlaced($order));
        }
    }
}
