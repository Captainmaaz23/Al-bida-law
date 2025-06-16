<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\MainController as MainController;
use Auth;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Exports\OrdersExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends MainController {

    public function __construct(OrderRepository $repository) {
        $this->repository = $repository;
    }

    public function index(Request $request, $list_type = 'All') {
        return $this->commonListing($request, $list_type);
    }

    public function open_listing(Request $request, $list_type = 'open') {
        return $this->commonListing($request, $list_type);
    }

    public function completed_listing(Request $request, $list_type = 'completed') {
        return $this->commonListing($request, $list_type);
    }

    public function declined_listing(Request $request, $list_type = 'declined') {
        return $this->commonListing($request, $list_type);
    }

    public function cancelled_listing(Request $request, $list_type = 'cancelled') {
        return $this->commonListing($request, $list_type);
    }

    private function commonListing(Request $request, $list_type) {
        $Auth_User = Auth::user();
        $list_title = 'Orders';
        $records = Order::leftJoin('restaurants', 'orders.rest_id', '=', 'restaurants.id')
                        ->leftJoin('app_users', 'orders.user_id', '=', 'app_users.id')
                        ->leftJoin('serve_tables', 'orders.table_id', '=', 'serve_tables.id')
                        ->select(['orders.id', 'orders.order_no', 'serve_tables.title as table_name', 'app_users.name as user_name', 'orders.final_value']);
        switch($list_type){
            case 'open':
                $filter_status = $this->_ORDER_PREPARING;
                $records = $records->where('orders.rest_id', $Auth_User->rest_id)->where('orders.status', $this->_ORDER_PREPARING);
                $list_title = $this->_ORDER_STATUSES[$filter_status].' '.$list_title;
                break;

            case 'completed':
                $filter_status = $this->_ORDER_SERVED;
                $records = $records->where('orders.rest_id', $Auth_User->rest_id)->where('orders.status', $this->_ORDER_SERVED);
                $list_title = $this->_ORDER_STATUSES[$filter_status].' '.$list_title;
                break;
            
            case 'declined':
                $filter_status = $this->_ORDER_DECLINED;
                $records = $records->where('orders.rest_id', $Auth_User->rest_id)->where('orders.status', $this->_ORDER_DECLINED);
                $list_title = $this->_ORDER_STATUSES[$filter_status].' '.$list_title;
                break;

            case 'cancelled':
                $filter_status = $this->_ORDER_CACELLED;
                $records = $records->where('orders.rest_id', $Auth_User->rest_id)->where('orders.status', $this->_ORDER_CACELLED);
                $list_title = $this->_ORDER_STATUSES[$filter_status].' '.$list_title;
                break;

            default:
                $records = $records->where('orders.rest_id', $Auth_User->rest_id)->where('orders.rest_id', $Auth_User->rest_id);
                $list_title = 'All '.$list_title;
                break;            
        }
        
        if ($request->has('rest_id') && $request->get('rest_id') != -1 && !empty($request->get('rest_id'))) {
            $records->where('orders.rest_id', $request->get('rest_id'));
        }

        if ($request->has('order_no') && $request->get('order_no') != -1 && !empty($request->get('order_no'))) {
            $records->where('orders.order_no', 'LIKE', '%' . $request->get('order_no') . '%');
        }

        if ($request->has('user_id') && $request->get('user_id') != -1 && !empty($request->get('user_id'))) {
            $records->where('orders.user_id', $request->get('user_id'));
        }

        if ($request->has('table_id') && $request->get('table_id') != -1 && !empty($request->get('table_id'))) {
            $records->where('orders.table_id', $request->get('table_id'));
        }

        if ($request->has('order_value') && $request->get('order_value') != -1 && !empty($request->get('order_value'))) {
            $records->where('orders.order_value', '>=', $request->get('order_value'));
        }

        if ($request->has('status') && $request->get('status') != -1 && !empty($request->get('status'))) {
            $records->where('orders.status', $request->get('status'));
        }
        $list_title = strtolower(trim(date('Y-m-d').' '.$list_title));
        $list_name = str_replace(' ', '-', $list_title);
        return Excel::download(new OrdersExport($records), $list_name.'.xlsx');
    }
}
