<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\MainController as MainController;
use App\Models\AppUser;
use App\Models\Restaurant;
use App\Models\ServeTable;
use App\Models\Order;
use Auth;
use Carbon\Carbon;

class HomeController extends MainController {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $Auth_User = Auth::user();

        if ($Auth_User->rest_id == 0) {
            return view('home');
        }
        elseif ($Auth_User->rest_id > 0) {

            $rest_id = $Auth_User->rest_id;
            $states_array = [];

            $today = Carbon::today();
            $yesterday = Carbon::yesterday();
            $previousday = Carbon::now()->subDays(2);
            $week_start = Carbon::now()->subWeek();

            $orders_today = Order::where('rest_id', $rest_id)->whereIN('status', $this->_ARRAY_ORDER_DASHBOARD)->where('created_at', '>=', $today)->get();
            $orders_yesterday = Order::where('rest_id', $rest_id)->whereIN('status', $this->_ARRAY_ORDER_DASHBOARD)->where('created_at', '>=', $yesterday)->where('created_at', '<', $today)->get();
            $orders_previous_day = Order::where('rest_id', $rest_id)->whereIN('status', $this->_ARRAY_ORDER_DASHBOARD)->where('created_at', '>=', $previousday)->where('created_at', '<', $yesterday)->get();
            $orders_last_week = Order::where('rest_id', $rest_id)->whereIN('status', $this->_ARRAY_ORDER_DASHBOARD)->where('created_at', '>=', $week_start)->get();

            // Today's stats
            $state_today = [
                'today_orders'     => $orders_today->count(),
                'yesterday_orders' => $orders_yesterday->count(),
                'total_orders'     => $orders_today->count(),
                'total_sales'      => $orders_today->sum('final_value'),
                'total_tax'        => $orders_today->sum('vat_value'),
                'total_earnings'   => $orders_today->sum('final_value') - $orders_today->sum('vat_value')
            ];
            $states_array['today'] = $state_today;

            // Yesterday's stats
            $previousday_name = $previousday->isoFormat('dddd');
            $state_yesterday = [
                'today_orders'       => $orders_today->count(),
                'yesterday_orders'   => $orders_yesterday->count(),
                'previousday_orders' => $orders_previous_day->count(),
                'previousday_name'   => $previousday_name,
                'total_orders'       => $orders_yesterday->count(),
                'total_sales'        => $orders_today->sum('final_value'),
                'total_tax'          => $orders_today->sum('vat_value'),
                'total_earnings'     => $orders_today->sum('final_value') - $orders_today->sum('vat_value')
            ];
            $states_array['yesterday'] = $state_yesterday;

            // Last week stats
            $week_data = [];
            foreach (range(6, 0, -1) as $i) {
                $day = Carbon::now()->subDays($i);
                $day_name = $day->isoFormat('dddd');
                $orders_day = $orders_last_week->filter(function ($order) use ($day) {
                    return $order->created_at->isSameDay($day);
                });

                $week_data["day{$i}_orders"] = $orders_day->count();
                $week_data["day{$i}_name"] = $day_name;
                $week_data["day{$i}_sales"] = $orders_day->sum('final_value');
                $week_data["day{$i}_tax"] = $orders_day->sum('vat_value');
                $week_data["day{$i}_earnings"] = $orders_day->sum('final_value') - $orders_day->sum('vat_value');
            }

            // Total stats for the week
            $week_data['total_orders'] = $orders_last_week->count();
            $week_data['total_sales'] = $orders_last_week->sum('final_value');
            $week_data['total_tax'] = $orders_last_week->sum('vat_value');
            $week_data['total_earnings'] = $week_data['total_sales'] - $week_data['total_tax'];

            $states_array['week'] = $week_data;

            // Monthly stats
            $day30 = Carbon::today();
            $created_at = Carbon::now()->subMonth();
            $total_orders = Order::where('rest_id', $rest_id)
                    ->whereIN('status', $this->_ARRAY_ORDER_DASHBOARD)
                    ->where('created_at', '>=', $created_at)
                    ->count();

            $total_sales = Order::where('rest_id', $rest_id)
                    ->whereIN('status', $this->_ARRAY_ORDER_DASHBOARD)
                    ->where('created_at', '>=', $created_at)
                    ->sum('final_value');

            $total_tax = Order::where('rest_id', $rest_id)
                    ->whereIN('status', $this->_ARRAY_ORDER_DASHBOARD)
                    ->where('created_at', '>=', $created_at)
                    ->sum('vat_value');

            $state_array = [];
            for ($i = 30; $i > 0; $i--) {
                $day = $day30->copy()->subDays($i);
                $day_name = $day->isoFormat('D');
                $day_orders = Order::where('rest_id', $rest_id)
                        ->whereIN('status', $this->_ARRAY_ORDER_DASHBOARD)
                        ->whereBetween('created_at', [$day->startOfDay()->toDateTimeString(), $day->endOfDay()->toDateTimeString()])
                        ->count();
                $state_array["day{$i}_orders"] = $day_orders;
                $state_array["day{$i}_name"] = $day_name;
            }

            $state_array['total_orders'] = $total_orders;
            $state_array['total_sales'] = $total_sales;
            $state_array['total_tax'] = $total_tax;
            $state_array['total_earnings'] = ($total_sales - $total_tax);

            $states_array['month'] = $state_array;

            // Yearly stats
            $months_data = [];
            for ($i = 12; $i > 0; $i--) {
                $monthStart = Carbon::today()->subMonths($i);
                $monthName = $monthStart->isoFormat('MMM');
                $orders_count = Order::where('rest_id', $rest_id)
                        ->whereIN('status', $this->_ARRAY_ORDER_DASHBOARD)
                        ->where('created_at', '>=', $monthStart->toDateTimeString())
                        ->where('created_at', '<', $monthStart->addMonth()->toDateTimeString())
                        ->count();

                $months_data[] = [
                    'month_name' => $monthName,
                    'orders'     => $orders_count
                ];
            }

            $total_sales = Order::where('rest_id', $rest_id)
                    ->whereIN('status', $this->_ARRAY_ORDER_DASHBOARD)
                    ->where('created_at', '>=', $created_at)
                    ->sum('final_value');

            $total_tax = Order::where('rest_id', $rest_id)
                    ->whereIN('status', $this->_ARRAY_ORDER_DASHBOARD)
                    ->where('created_at', '>=', $created_at)
                    ->sum('vat_value');

            $total_earnings = $total_sales - $total_tax;
            
            $state_array = [
                'total_orders'   => array_sum(array_column($months_data, 'orders')),
                'total_sales'    => $total_sales,
                'total_tax'      => $total_tax,
                'total_earnings' => $total_earnings
            ];
            $i=0;
            foreach($months_data as $month){
                $i++;
                $state_array["month{$i}_orders"] = $month['orders'];
                $state_array["month{$i}_name"] = $month['month_name'];
            }
            $states_array['year'] = $state_array;

            // Other data for dashboard
            $active_waiters_count = AppUser::where('user_type', 1)->where('status', 1)->count();
            $active_devices_count = 0;//AppUser::where('user_type', 2)->where('status', 1)->count();
            $tables_count = ServeTable::where('rest_id', $rest_id)->count();

            $pending_orders_count = Order::where('rest_id', $rest_id)->whereIn('status', $this->_ARRAY_ORDER_OPEN)->count();
            $declined_orders_count = Order::where('rest_id', $rest_id)->where('status', $this->_ORDER_DECLINED)->count();
            $cancelled_orders_count = Order::where('rest_id', $rest_id)->where('status', $this->_ORDER_CACELLED)->count();
            $completed_orders_count = Order::where('rest_id', $rest_id)->where('status', $this->_ORDER_SERVED)->count();

            $restaurants_array = Restaurant::all();
            $users_array = AppUser::get();
            $tables_array = ServeTable::where('rest_id', $rest_id)->get();

            return view('dashboard', compact(
                            "states_array",
                            "active_waiters_count",
                            "active_devices_count",
                            "tables_count",
                            "pending_orders_count",
                            "declined_orders_count",
                            "cancelled_orders_count",
                            "completed_orders_count",
                            "restaurants_array",
                            "users_array",
                            "tables_array"
            ));
        }
    }
}
