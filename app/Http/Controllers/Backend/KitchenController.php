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
                    if(in_array($Model_Data->status,$this->_ARRAY_ORDER_DECLINEABLE)) {
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
                            'scheduled_time' => strtotime(Order::find($request->id)->pickup_time) - ((int)$request->time * 60),
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
                            'pickup_time'      => Carbon::now()->addMinutes((int)$request->time),
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

    public function decline_order_batch(Request $request) {
        $order_ids = explode(",", $request->id);
        $orders = Order::where('rest_id', Auth::user()->rest_id)
            ->where(function ($query) use ($order_ids) {
                $query->where(function ($q) use ($order_ids) {
                    $q->whereIn('status', $this->_ARRAY_ORDER_DECLINEABLE)
                      ->whereIn('id', $order_ids);
                });
            })
            ->select(['orders.id'])
            ->get();
        
        if(!empty($orders)) {
            Order::whereIn('status', $this->_ARRAY_ORDER_DECLINEABLE)
                    ->whereIn('id', $order_ids)
                    ->update([
                        'declined_time'  => Carbon::now(),
                        'decline_reason' => $request->reason,
                        'status'         => $this->_ORDER_DECLINED,
                    ]);

            //$this->generateInvoiceBatch($id);
            return true;
        }
        return false;
    }

    public function order_scheduled_batch(Request $request) {
        $order_ids = explode(",", $request->id);
        $orders = Order::where('rest_id', Auth::user()->rest_id)
            ->where(function ($query) use ($order_ids) {
                $query->where(function ($q) use ($order_ids) {
                    $q->where('status', $this->_ORDER_CONFIRMED)
                      ->whereIn('id', $order_ids);
                });
            })
            ->select(['orders.id'])
            ->get();
        
        if(!empty($orders)) {
            Order::where('status', $this->_ORDER_CONFIRMED)
                ->whereIn('id', $order_ids)
                ->update([
                    'scheduled_time' => strtotime(Order::find($request->id)->pickup_time) - ((int)$request->time * 60),
                    'accepted_time'  => Carbon::now(),
                    'status'         => $this->_ORDER_ACCEPTED
                ]);

            //$this->generateInvoiceBatch($id);
            return true;
        }
        return false;
    }

    public function move_to_outgoing_orders_batch(Request $request) {
        $order_ids = explode(",", $request->id);
        $orders = Order::where('rest_id', Auth::user()->rest_id)
            ->where(function ($query) use ($order_ids) {
                $query->where(function ($q) use ($order_ids) {
                    $q->where('status', $this->_ORDER_ACCEPTED)
                      ->whereIn('id', $order_ids);
                });
            })
            ->select(['orders.id'])
            ->get();
            
        if(!empty($orders)) {
            Order::where('status', $this->_ORDER_ACCEPTED)
                ->whereIn('id', $order_ids)
                ->update([
                    'preparation_time' => Carbon::now(),
                    'status'           => $this->_ORDER_PREPARING
                ]);

            //$this->generateInvoiceBatch($id);
            return true;
        }
        return false;
    }

    public function order_preparing_batch(Request $request) {
        $order_ids = explode(",", $request->id);
        $orders = Order::where('rest_id', Auth::user()->rest_id)
            ->where(function ($query) use ($order_ids) {
                $query->where(function ($q) use ($order_ids) {
                    $q->where('status', $this->_ORDER_CONFIRMED)
                      ->whereIn('id', $order_ids);
                });
            })
            ->select(['orders.id'])
            ->get();
        
        if(!empty($orders)) {
            Order::where('status', $this->_ORDER_CONFIRMED)
                    ->whereIn('id', $order_ids)
                    ->update([
                        'pickup_time'      => Carbon::now()->addMinutes((int)$request->time),
                        'accepted_time'    => Carbon::now(),
                        'preparation_time' => Carbon::now(),
                        'status'           => $this->_ORDER_PREPARING
                    ]);

            //$this->generateInvoiceBatch($id);
            return true;
        }
        return false;
    }

    public function order_reschedule_batch(Request $request) {
        $order_ids = explode(",", $request->id);
        $orders = Order::where('rest_id', Auth::user()->rest_id)
            ->where(function ($query) use ($order_ids) {
                $query->where(function ($q) use ($order_ids) {
                    $q->where('status', $this->_ORDER_PREPARING)
                      ->whereIn('id', $order_ids);
                });
            })
            ->select(['orders.id'])
            ->get();
        
        if(!empty($orders)) {
            Order::where('status', $this->_ORDER_PREPARING)
                    ->whereIn('id', $order_ids)
                    ->update([
                        'pickup_time'      => Carbon::now()->addMinutes((int)$request->time),
                        //'accepted_time'    => Carbon::now(),
                        //'preparation_time' => Carbon::now(),
                        //'status'           => $this->_ORDER_PREPARING
                    ]);

            //$this->generateInvoiceBatch($id);
            return true;
        }
        return false;
    }

    public function order_ready_batch(Request $request) {
        $order_ids = explode(",", $request->id);
        $orders = Order::where('rest_id', Auth::user()->rest_id)
            ->where(function ($query) use ($order_ids) {
                $query->where(function ($q) use ($order_ids) {
                    $q->where('status', $this->_ORDER_PREPARING)
                      ->whereIn('id', $order_ids);
                });
            })
            ->select(['orders.id'])
            ->get();
        
        if(!empty($orders)) {
            Order::where('status', $this->_ORDER_PREPARING)
                    ->whereIn('id', $order_ids)
                    ->update([
                            'ready_time' => Carbon::now(),
                            'status'     => $this->_ORDER_READY
                    ]);

            //$this->generateInvoiceBatch($id);
            return true;
        }
        return false;
    }

    public function pickup_orders_batch(Request $request) {
        $order_ids = explode(",", $request->id);
        $orders = Order::where('rest_id', Auth::user()->rest_id)
            ->where(function ($query) use ($order_ids) {
                $query->where(function ($q) use ($order_ids) {
                    $q->where('status', $this->_ORDER_READY)
                      ->whereIn('id', $order_ids);
                });
            })
            ->select(['orders.id'])
            ->get();
        
        if(!empty($orders)) {
            Order::where('status', $this->_ORDER_READY)
                    ->whereIn('id', $order_ids)
                    ->update([
                            'picked_time' => Carbon::now(),
                            'status'      => $this->_ORDER_READY_TO_SERVE
                    ]);

            //$this->generateInvoiceBatch($id);
            return true;
        }
        return false;
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
        $ready_order_ids = $request->ready_order_ids;
        $orders = Order::where('rest_id', Auth::user()->rest_id)
            ->where(function ($query) use ($incoming_order_ids, $outgoing_order_ids, $ready_order_ids) {
                $query->where(function ($q) use ($incoming_order_ids) {
                    $q->where('status', $this->_ORDER_CONFIRMED)
                      ->whereNotIn('id', explode(",", $incoming_order_ids));
                })
                ->orWhere(function ($q) use ($outgoing_order_ids) {
                    $q->where('status', $this->_ORDER_PREPARING)
                      ->whereNotIn('id', explode(",", $outgoing_order_ids));
                })
                ->orWhere(function ($q) use ($ready_order_ids) {
                    $q->where('status', $this->_ORDER_READY)
                      ->whereNotIn('id', explode(",", $ready_order_ids));
                });
            })
            ->select(['orders.id'])
            ->orderBy('orders.id', 'desc')
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
        $order_ids = explode(",", $id);
        update_order_status_automatically();
        
        $json_Data = [];

        $orders = Order::where('rest_id', Auth::user()->rest_id)
                ->where('status', $this->_ORDER_READY)
                //->when($id != 0, fn($query) => $query->where('id', $id))
                ->whereIn('id', $order_ids)
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
        $order_ids = explode(",", $id);
        update_order_status_automatically();
        
        $json_Data = [];

        $orders = Order::where('rest_id', Auth::user()->rest_id)
                ->where('status', $this->_ORDER_READY)
                ->whereIn('id', $order_ids)
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
}