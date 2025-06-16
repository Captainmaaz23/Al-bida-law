<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\MainController as MainController;
use Auth;
use Flash;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\ServeTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\OrderRepository;
use Carbon\Carbon;

class KitchenController extends MainController {

    private $repository;
    private $view_route = "orders.show";
    private $msg_not_found = "Order not found. Please try again.";
    private $list_permission = "orders-listing";
    private $status_permission = "orders-status";
    private $list_permission_error_message = "Error: You are not authorized to View Listings of Orders. Please Contact Administrator.";
    private $status_permission_error_message = "Error: You are not authorized to change status of Order. Please Contact Administrator.";

    public function __construct(OrderRepository $repository) {
        $this->repository = $repository;
    }

    private function is_not_authorized($id, $Auth_User) {
        return $Auth_User->rest_id != 0 && !Order::where(['id' => $id, 'rest_id' => $Auth_User->rest_id])->exists();
    }

    public function clear_pending_orders() {
        if (!$this->userCan('clear_pending_orders')) {
            Flash::error($this->list_permission_error_message);
            return redirect()->route($this->redirect_home);
        }
        $Auth_User = Auth::user();
        Order::whereIn('status', $this->_ARRAY_ORDER_OPEN)->update([
            'declined_time'  => Carbon::now(),
            'decline_reason' => 'clear pending orders by admin',
            'status'     => $this->_ORDER_DECLINED,
            'updated_by' => $Auth_User->id,
        ]);

        Flash::success('All pending orders cleared successfully.');
        return redirect()->route($this->redirect_home);
    }

    public function kitchenDashboard() {
        if (!$this->userCan($this->list_permission)) {
            Flash::error($this->list_permission_error_message);
            return redirect()->route($this->redirect_home);
        }
        update_order_status_automatically();
        $Auth_User = Auth::user();

        if ($Auth_User->rest_id == 0) {
            return redirect()->route($this->redirect_home);
        }

        $recordsExists = Order::exists() ? 1 : 0;
        $restaurants_array = Restaurant::where('status', $this->_ACTIVE)->orderBy('name')->pluck('name', 'id');
        $tables_array = ServeTable::where('status', $this->_ACTIVE)->orderBy('title')->pluck('title', 'id');
        $users_array = DB::table('app_users')
                        ->join('orders', 'app_users.id', '=', 'orders.user_id')
                        ->select('app_users.name', 'orders.user_id', 'orders.id', 'orders.status')
                        ->distinct()->get();

        return view('restaurants.kitchen.listing', compact("recordsExists", "restaurants_array", "users_array", "tables_array"));
    }

    public function check_user_arrived() {
        $rest_id = Auth::user()->rest_id;
        $user = Order::where('rest_id', $rest_id)->where('user_arrived', '=', '1')->get();

        if (count($user) >= 1) {
            $str = '';
            foreach ($user as $users) {
                $str .= '<li>
                <a class="text-dark d-flex py-2" href="' . route($this->view_route, $users->id) . '">
                <div class="flex-shrink-0 me-2 ms-3">
                    <i class="fa fa-fw fa-check-circle text-success"></i>
                </div>
                <div class="flex-grow-1 pe-2">
                    <div class="fw-semibold">' . $users->user_arrived . ' User Arrived</div>
                    <span class="fw-medium text-muted">' . Carbon::parse($users->update_at)->diffForHumans() . '</span>
                </div>
                </a>
            </li>';
            }
            return $str;
        }
    }

    public function kitchen_status($id, $type, $request = NULL) {
        if (!$this->userCan($this->status_permission)) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->redirect_home);
        }
        
        $Auth_User = Auth::user();
        $Model_Data = Order::find($id);
        if (!$Model_Data || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }
        
        switch($type){
                case 'decline':
                    if($Model_Data->status == $this->_ORDER_CONFIRMED) {
                        $Model_Data->update([
                            'declined_time'  => Carbon::now(),
                            'decline_reason' => $request->reason,
                            'status'         => $this->_ORDER_DECLINED,
                        ]);

                        //$this->generateInvoice($id);
                        return true;
                    }
                break;
            
            case 'scheduled':
                    if($Model_Data->status == $this->_ORDER_CONFIRMED) {
                        $Model_Data->update([
                            'scheduled_time' => strtotime(Order::find($request->id)->pickup_time) - ($request->time * 60),
                            'accepted_time'  => Carbon::now(),
                            'status'         => $this->_ORDER_ACCEPTED
                        ]);

                        //$this->generateInvoice($id);
                        return true;
                    }
                break;
            
            case 'outgoing':
                    if($Model_Data->status == $this->_ORDER_ACCEPTED) {
                        $Model_Data->update([
                            'preparation_time' => Carbon::now(),
                            'status'           => $this->_ORDER_PREPARING
                        ]);

                        //$this->generateInvoice($id);
                        return true;
                    }
                break;
            
            case 'preparing':
                    if($Model_Data->status == $this->_ORDER_CONFIRMED) {
                        $Model_Data->update([
                            'pickup_time'      => time() + ($request->time * 60),
                            'accepted_time'    => Carbon::now(),
                            'preparation_time' => Carbon::now(),
                            'status'           => $this->_ORDER_PREPARING
                        ]);

                        //$this->generateInvoice($id);
                        return true;
                    }
                break;
            
            case 'ready':
                    if($Model_Data->status == $this->_ORDER_PREPARING) {
                        $Model_Data->update([
                            'ready_time' => Carbon::now(),
                            'status'     => $this->_ORDER_READY
                        ]);

                        $this->generateInvoice($id);
                        return true;
                    }
                break;
            
            case 'ready_to_serve':
                    if($Model_Data->status == $this->_ORDER_READY) {
                        $Model_Data->update([
                            'picked_time' => Carbon::now(),
                            'status'      => $this->_ORDER_READY_TO_SERVE
                        ]);

                        $this->generateInvoice($id);
                        return true;
                    }
                break;
            
            case 'pickup':
                    if($Model_Data->status == $this->_ORDER_READY) {
                        $Model_Data->update([
                            'picked_time' => Carbon::now(),
                            'status'      => $this->_ORDER_READY_TO_SERVE
                        ]);

                        $this->generateInvoice($id);
                        return true;
                    }
                break;
            
            case 'served':
                    $Model_Data->update([
                        'collected_time' => Carbon::now(),
                        'status'         => $this->_ORDER_SERVED,
                    ]);

                    $this->generateInvoice($id);
                    
                    Flash::success('Status Updated successfully.');
                    return redirect(route($this->home_route));
                break;
            
            default:
                break;
            
        }

        return false;
    }

    public function decline_order(Request $request) {
        return $this->kitchen_status($request->id, 'decline', $request);
    }

    public function order_scheduled(Request $request) {
        return $this->kitchen_status($request->id, 'scheduled', $request);
    }

    public function move_to_outgoing_orders(Request $request) {
        return $this->kitchen_status($request->id, 'outgoing', $request);
    }

    public function order_preparing(Request $request) {
        return $this->kitchen_status($request->id, 'preparing', $request);
    }

    public function order_ready(Request $request) {
        return $this->kitchen_status($request->id, 'ready', $request);
    }

    public function pickup_orders(Request $request) {
        return $this->kitchen_status($request->id, 'pickup', $request);
    }
    
    public function order_served($id) {
        return $this->kitchen_status($id, 'served');
    }
    
    //    

    public function check_pin($id, $pin) {
        update_order_status_automatically();

        /* $check = 0;
          $Data = Order::where('id',$id)->where('pin_no',$pin)->count();
          if($Data > 0){
          $check = 1;
          } */

        $check = 1;
        return $check;
    }

    //

    public function get_combined_orders(Request $request) {
        update_order_status_automatically();
        
        $json_Data = [];
        
        $incoming_order_ids = $request->incoming_order_ids;
        $outgoing_order_ids = $request->outgoing_order_ids;
        $orders = Order::where('rest_id', Auth::user()->rest_id)
            ->where(function ($query) use ($incoming_order_ids, $outgoing_order_ids) {
                $query->where(function ($q) use ($incoming_order_ids) {
                    $q->where('status', $this->_ORDER_CONFIRMED)
                      ->whereNotIn('id', explode(",", $incoming_order_ids));
                })
                ->orWhere(function ($q) use ($outgoing_order_ids) {
                    $q->where('status', $this->_ORDER_PREPARING)
                      ->whereNotIn('id', explode(",", $outgoing_order_ids));
                });
            })
            ->select(['orders.id'])
            ->get();
        
        if(!empty($orders)) {        
            $order_ids = array();
            foreach ($orders as $data) {
                $order_ids[] = $data->id;
            }
            $json_Data = $this->get_order_details_json($order_ids);
        }
        echo json_encode($json_Data);
    }

    private function get_order_details_json($order_ids, $show_timer = 1) {        
        $json_Data = [];
        $records = Order::leftjoin('restaurants', 'orders.rest_id', '=', 'restaurants.id')
                ->leftjoin('serve_tables', 'orders.table_id', '=', 'serve_tables.id')
                ->leftjoin('app_users', 'orders.user_id', '=', 'app_users.id')
                ->select(['orders.*', 'restaurants.name as rest_name',
                    'serve_tables.title as table_name', 'serve_tables.icon as table_icon',
                    'app_users.user_type as user_type', 'app_users.name as user_name', 'app_users.phone as user_phone', 'app_users.photo_type', 'app_users.photo as user_photo'
                ])
                ->whereIn('orders.id', $order_ids)
                ->get();
        foreach ($records as $data) :
            $order = $this->get_order_details_common($data);

            $pickup_time = $order['pickup_time'];
            $pickup_duration = ceil(($pickup_time - time()) / 60);
            $order["show_accept_button"] = ($pickup_duration <= 60) ? 1 : 0;
            if($show_timer){                
                $pickup_time = $order['status'] == ORDER_STATUS_ARRAY()['Accepted'] ? $order['scheduled_time'] : $order['pickup_time'];
                $pickup_duration = ceil(($pickup_time - time()) / 60);
                $pickup_time = callPickupTime($pickup_time);

                $order["timer_col_class"] = $pickup_duration <= 60 ? '3' : '4';
                $order["timer_col_style"] = $pickup_duration <= 60 ? 'margin-left:30px;' : '';
                $order["is_timer"] = ($pickup_duration <= 60) ? 1 : 0;
                $order["timer_description"] = ($pickup_duration <= 60) ? $pickup_duration : $pickup_time;                    
            }
            $order["show_timer"] = $show_timer;
            
            $json_Data[] = $order;
        endforeach;
        return $json_Data;
    }

    public function get_incoming_orders($ids) {
        update_order_status_automatically();
        
        $json_Data = [];
        
        $orders = Order::where('rest_id', Auth::user()->rest_id)
                ->where('status', $this->_ORDER_CONFIRMED)
                ->whereNotIn('id', explode(",", $ids))
                ->select(['orders.id'])
                ->get();
        
        if(!empty($orders)) {        
            $order_ids = array();
            foreach ($orders as $data) {
                $order_ids[] = $data->id;
            }
            $json_Data = $this->get_order_details_json($order_ids);
        }
        echo json_encode($json_Data);
    }

    public function outgoing_orders($id) {
        update_order_status_automatically();
        
        $json_Data = [];

        $orders = Order::where('rest_id', Auth::user()->rest_id)
                ->where('status', $this->_ORDER_PREPARING)
                ->whereNotIn('id', explode(",", $id))
                ->select(['orders.id'])
                ->get();
        
        if(!empty($orders)) {        
            $order_ids = array();
            foreach ($orders as $data) {
                $order_ids[] = $data->id;
            }
            $json_Data = $this->get_order_details_json($order_ids);
        }
        echo json_encode($json_Data);
    }

    public function scheduled_orders($id) {
        update_order_status_automatically();
        
        $json_Data = [];

        $orders = Order::where('rest_id', Auth::user()->rest_id)
                ->where('status', $this->_ORDER_ACCEPTED)
                //->when($id != 0, fn($query) => $query->where('id', $id))
                ->where('id', $id)
                ->select(['orders.id'])
                ->get();
        
        if(!empty($orders)) {        
            $order_ids = array();
            foreach ($orders as $data) {
                $order_ids[] = $data->id;
            }
            $json_Data = $this->get_order_details_json($order_ids);
        }
        echo json_encode($json_Data);
    }

    public function ready_orders($id) {
        update_order_status_automatically();
        
        $json_Data = [];

        $orders = Order::where('rest_id', Auth::user()->rest_id)
                ->where('status', $this->_ORDER_READY)
                //->when($id != 0, fn($query) => $query->where('id', $id))
                ->where('id', $id)
                ->select(['orders.id'])
                ->get();
        
        if(!empty($orders)) {        
            $order_ids = array();
            foreach ($orders as $data) {
                $order_ids[] = $data->id;
            }
            $json_Data = $this->get_order_details_json($order_ids, 0);
        }
        echo json_encode($json_Data);
    }

    public function ready_order_details($id) {
        update_order_status_automatically();
        
        $json_Data = [];

        $orders = Order::where('rest_id', Auth::user()->rest_id)
                ->where('status', $this->_ORDER_READY)
                ->where('id', $id)
                ->select(['orders.id'])
                ->get();
        
        if(!empty($orders)) {        
            $order_ids = array();
            foreach ($orders as $data) {
                $order_ids[] = $data->id;
            }
            $json_Data = $this->get_order_details_json($order_ids, 0);
        }
        echo json_encode($json_Data);
    }
    
    
    /////

    public function get_incoming_orders_old($ids) {
        update_order_status_automatically();
        
        $orders = Order::where('rest_id', Auth::user()->rest_id)
                ->where('status', $this->_ORDER_CONFIRMED)
                ->whereNotIn('id', explode(",", $ids))
                ->get();
        
        if(!empty($orders)) :        
            $OrderData = array();
            foreach ($orders as $data) {
                $OrderData[] = $this->get_order_details_common($data);
            }
            
            foreach ($OrderData as $order) :
                $pickup_time = $order['pickup_time'];
                $pickup_duration = ceil(($pickup_time - time()) / 60);

                $pickup_time = callPickupTime($pickup_time);
                
                ?>
                <div class="col-sm-12 order_no incoming_order" id="<?php echo $order['id']; ?>" data-lastid="<?php echo $order['id']; ?>" data-lat="<?php echo $order['lat']; ?>" data-lng="<?php echo $order['lng']; ?>">

                    <?php get_common_details_html($order);?>

                    <div class="row nomodal">
                        <div class="col-sm-12">

                            <div class="w50 fl_left">
                                <a class="btn btn-outline-danger decline_btn" data-toggle="modal" data-target="#decline_modal" ui-toggle-class="bounce" ui-target="#decline_animate">Decline</a>
                            </div>
                            <?php if ($pickup_duration <= 60): ?>
                                <div class="w50 fl_left">
                                    <a class="btn btn-primary accept_btn" data-toggle="modal" data-target="#accept_modal" ui-toggle-class="bounce" ui-target="#animate" >Accept</a>
                                </div>
                            <?php else: ?>
                                <div class="w50 fl_left">
                                    <a class="btn btn-primary schedule_btn" data-toggle="modal" data-target="#schedule_modal" ui-toggle-class="bounce" ui-target="#animate" >Schedule</a>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                    <div class="row nomodal">
                        <div class="col-sm-12">
                            <hr>
                        </div>
                    </div>
                </div>
                <?php
            endforeach;
        endif;
    }

    public function outgoing_orders_old($id) {
        update_order_status_automatically();

        $orders = Order::where('rest_id', Auth::user()->rest_id)
                ->where('status', $this->_ORDER_PREPARING)
                ->whereNotIn('id', explode(",", $id))
                ->get();
        
        if(!empty($orders)) :        
            $OrderData = array();
            foreach ($orders as $data) {
                $OrderData[] = $this->get_order_details_common($data);
            }
            
            foreach ($OrderData as $order) :
                ?>
                <div class="col-sm-12 order_no outgoing_order" id="<?php echo $order['id']; ?>" data-lastid="<?php echo $order['id']; ?>" data-lat="<?php echo $order['lat']; ?>" data-lng="<?php echo $order['lng']; ?>">

                    <?php get_common_details_html($order); ?>

                    <div class="row nomodal">
                        <div class="col-sm-12">
                            <div class="w88 txt_center m_auto">
                                <a class="btn btn-primary ready_btn" style="display: block;width: 100%;">Ready to Serve</a>
                            </div>
                        </div>
                    </div>


                    <div class="row nomodal">
                        <div class="col-sm-12">
                            <hr>
                        </div>
                    </div>
                </div>
                <?php
            endforeach;
        endif;
    }

    public function scheduled_orders_old($id) {
        update_order_status_automatically();

        $orders = Order::where('rest_id', Auth::user()->rest_id)
                ->where('status', $this->_ORDER_ACCEPTED)
                //->when($id != 0, fn($query) => $query->where('id', $id))
                ->where('id', $id)
                ->get();
        
        if(!empty($orders)) :        
            $OrderData = array();
            foreach ($orders as $data) {
                $OrderData[] = $this->get_order_details_common($data);
            }
            
            foreach ($OrderData as $order) :
                ?>
                <div class="col-sm-12 order_no scheduled_order" id="<?php echo $order['id']; ?>" data-lastid="<?php echo $order['id']; ?>" data-lat="<?php echo $order['lat']; ?>" data-lng="<?php echo $order['lng']; ?>">

                    <?php get_common_details_html($order); ?>

                    <div class="row nomodal">
                        <div class="col-sm-12">
                            <div class="w88 txt_center m_auto">
                                <a class="btn btn-primary move_btn" style="display: block;width: 100%;">Move to Outgoing</a>
                            </div>
                        </div>
                    </div>

                    <div class="row nomodal">
                        <div class="col-sm-12">
                            <hr>
                        </div>
                    </div>
                </div>
                <?php
            endforeach;
        endif;
    }

    public function ready_orders_old($id) {
        update_order_status_automatically();

        $orders = Order::where('rest_id', Auth::user()->rest_id)
                ->where('status', $this->_ORDER_READY)
                //->when($id != 0, fn($query) => $query->where('id', $id))
                ->where('id', $id)
                ->get();
        
        if(!empty($orders)) :        
            $OrderData = array();
            foreach ($orders as $data) {
                $OrderData[] = $this->get_order_details_common($data);
            }
            
            foreach ($OrderData as $order) :
                $pickup_time = $order['pickup_time'];
                $pickup_duration = ceil(($pickup_time - time()) / 60);

                $pickup_time = callPickupTime($pickup_time);

                $created_at = strtotime($order["created_at"]);
                $created_at = calExpiryDay($created_at);
                ?>
                <div class="col-sm-12 order_no ready_order" id="<?php echo $order['id']; ?>" data-lastid="<?php echo $order['id']; ?>" >
                    <div class="row">
                        <div class="col-sm-12">
                            <small>Order # <?php echo $order["order_no"]; ?></small>
                            <br />                
                            <small>For <?php echo get_table_name($order["table_id"]); ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo get_user_div_for_kitchen($order["user_id"], $created_at); ?>
                        </div>
                    </div>

                    <div class="row nomodal">
                        <div class="col-sm-12">
                            <small> Order Value: <?php echo $order["total_value"]; ?> </small>
                            <br />
                            <small> VAT Value: <?php echo $order["vat_value"]; ?> </small>
                            <br />
                            <small> Service Charges: <?php echo $order["service_charges"]; ?> </small>
                            <br />
                            <small> Total Value: </small>
                            <br />
                            <h3 class="or_value"><?php echo $order["final_value"]; ?> </h3>
                        </div>
                    </div>

                    <div class="row nomodal">
                        <div class="col-sm-12">

                            <div class="w88 txt_center m_auto">
                                <a class="btn btn-primary pickup_btn" data-toggle="modal" data-target="#ready_modal" ui-toggle-class="bounce" ui-target="#ready_animate" >Serve</a>
                            </div>
                        </div>
                    </div>

                    <div class="row nomodal">
                        <div class="col-sm-12">
                            <hr>
                        </div>
                    </div>
                </div>
                <?php
            endforeach;
        endif;
    }

    public function ready_order_details_old($id) {
        update_order_status_automatically();

        $orders = Order::where('rest_id', Auth::user()->rest_id)
                ->where('status', $this->_ORDER_READY)
                ->where('id', $id)
                ->get();
        
        if(!empty($orders)) :        
            $OrderData = array();
            foreach ($orders as $data) {
                $OrderData[] = $this->get_order_details_common($data);
            }
            
            foreach ($OrderData as $order) :
                ?>
                <div class="col-sm-12 order_no" data-lastid="<?php echo $order['id']; ?>" data-lat="<?php echo $order['lat']; ?>" data-lng="<?php echo $order['lng']; ?>">
                    
                    <?php get_common_details_html($order, false); ?>
                    
                </div>
                <?php
            endforeach;
        endif;
    }
}