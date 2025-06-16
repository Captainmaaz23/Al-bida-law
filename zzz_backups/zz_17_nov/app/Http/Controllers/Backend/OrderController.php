<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\MainController as MainController;
use PDF;
use Storage;
use Auth;
use Flash;
use Yajra\DataTables\Facades\DataTables as Datatables;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\ServeTable;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\OrderRepository;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Carbon\Carbon;

class OrderController extends MainController {

    private $repository;
    private $views_path = "restaurants.orders";
    private $home_route = "orders.index";
    private $create_route = "orders.create";
    private $edit_route = "orders.edit";
    private $view_route = "orders.show";
    private $delete_route = "orders.destroy";
    private $msg_created = "Order added successfully.";
    private $msg_updated = "Order updated successfully.";
    private $msg_deleted = "Order deleted successfully.";
    private $msg_not_found = "Order not found. Please try again.";
    private $msg_required = "Please fill all required fields.";
    private $msg_exists = "Record Already Exists with same name";
    private $list_permission = "orders-listing";
    private $add_permission = "orders-add";
    private $edit_permission = "orders-edit";
    private $view_permission = "orders-view";
    private $status_permission = "orders-status";
    private $delete_permission = "orders-delete";
    private $list_permission_error_message = "Error: You are not authorized to View Listings of Orders. Please Contact Administrator.";
    private $add_permission_error_message = "Error: You are not authorized to Add Order. Please Contact Administrator.";
    private $edit_permission_error_message = "Error: You are not authorized to Update Order. Please Contact Administrator.";
    private $view_permission_error_message = "Error: You are not authorized to View Order details. Please Contact Administrator.";
    private $status_permission_error_message = "Error: You are not authorized to change status of Order. Please Contact Administrator.";
    private $delete_permission_error_message = "Error: You are not authorized to Delete Order. Please Contact Administrator.";

    public function __construct(OrderRepository $repository) {
        $this->repository = $repository;
    }

    public function index($list_type = 'All') {
        return $this->commonListing($list_type);
    }

    public function open_listing($list_type = 'open') {
        return $this->commonListing($list_type);
    }

    public function completed_listing($list_type = 'completed') {
        return $this->commonListing($list_type);
    }

    public function declined_listing($list_type = 'declined') {
        return $this->commonListing($list_type);
    }

    public function cancelled_listing($list_type = 'cancelled') {
        return $this->commonListing($list_type);
    }

    private function commonListing($list_type) {
        if (!$this->userCan($this->list_permission)) {
            Flash::error($this->list_permission_error_message);
            return redirect()->route($this->redirect_home);
        }
        update_order_status_automatically();

        $Auth_User = Auth::user();
        
        $filter_array = $this->_ORDER_STATUSES;
        $filter_array[-1] = 'Select';
        $filter_status = -1;
        $list_title = 'Orders';
        $recordsExists = 0;
        switch($list_type){
            case 'open':
                $filter_status = $this->_ORDER_PREPARING;
                $recordsExists = Order::where('rest_id', $Auth_User->rest_id)->where('status', $this->_ORDER_PREPARING)->exists();
                $list_title = $this->_ORDER_STATUSES[$filter_status].' '.$list_title;
                break;

            case 'completed':
                $filter_status = $this->_ORDER_SERVED;
                $recordsExists = Order::where('rest_id', $Auth_User->rest_id)->where('status', $this->_ORDER_SERVED)->exists();
                $list_title = $this->_ORDER_STATUSES[$filter_status].' '.$list_title;
                break;
            
            case 'declined':
                $filter_status = $this->_ORDER_DECLINED;
                $recordsExists = Order::where('rest_id', $Auth_User->rest_id)->where('status', $this->_ORDER_DECLINED)->exists();
                $list_title = $this->_ORDER_STATUSES[$filter_status].' '.$list_title;
                break;

            case 'cancelled':
                $filter_status = $this->_ORDER_CACELLED;
                $recordsExists = Order::where('rest_id', $Auth_User->rest_id)->where('status', $this->_ORDER_CACELLED)->exists();
                $list_title = $this->_ORDER_STATUSES[$filter_status].' '.$list_title;
                break;

            default:
                $recordsExists = Order::where('rest_id', $Auth_User->rest_id)->exists();
                $list_title = 'All '.$list_title;
                break;            
        }

        $restaurants_array = Restaurant::where('status', 1)->orderBy('name')->pluck('name', 'id');
        $tables_array = ServeTable::where('status', 1)->orderBy('title')->pluck('title', 'id');

        $user_ids = DB::table('app_users')
                ->join('orders', 'app_users.id', '=', 'orders.user_id')
                ->where('orders.status', '>=', 3)
                ->distinct()
                ->pluck('orders.user_id');

        $users_array = DB::table('app_users')
                ->whereIn('app_users.id', $user_ids)
                ->select('app_users.id as user_id', 'app_users.name')
                ->get();

        return view($this->views_path . '.listing', compact("recordsExists", "list_title", "filter_status", "filter_array", "restaurants_array", "users_array", "tables_array"));
    }

    public function userOrders($id, $r_id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $Model_Data = Order::leftJoin('restaurants', 'orders.rest_id', '=', 'restaurants.id')
                    ->leftJoin('app_users', 'orders.user_id', '=', 'app_users.id')
                    ->select(['orders.id as o_id', 'orders.final_value', 'orders.total_value', 'orders.status', 'orders.created_at',
                        'orders.rest_id as r_id', 'app_users.id as u_id', 'app_users.name as u_name', 'app_users.phone as u_phone',
                        'app_users.email as u_email', 'app_users.username as user_name'])
                    ->where(['orders.user_id' => $id, 'orders.rest_id' => $r_id])
                    ->get();

            if ($Model_Data->isEmpty()) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $order_ids = $Model_Data->pluck('o_id');
            $Order_Details = OrderDetail::leftJoin('items', 'order_details.item_id', '=', 'items.id')
                    ->whereIn('order_details.order_id', $order_ids)
                    ->select(['order_details.id', 'order_details.order_id', 'order_details.quantity', 'order_details.item_value',
                        'order_details.discount', 'order_details.total_value', 'order_details.created_at', 'items.name as item_name'])
                    ->orderBy('order_details.order_id', 'asc')
                    ->get();

            $order_count = $Model_Data->count();

            return view($this->views_path . '.user_orders', compact('Model_Data', 'Order_Details', 'order_count'));
        }
        else {
            Flash::error($this->view_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function restOrders($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $Model_Data = Order::leftJoin('restaurants', 'orders.rest_id', '=', 'restaurants.id')
                    ->leftJoin('app_users', 'orders.user_id', '=', 'app_users.id')
                    ->select(['orders.id as o_id', 'orders.final_value', 'orders.total_value', 'orders.status', 'orders.created_at',
                        'app_users.id as u_id', 'app_users.name as u_name', 'app_users.phone as u_phone',
                        'app_users.email as u_email', 'app_users.username as user_name'])
                    ->where(['orders.rest_id' => $id])
                    ->get();

            if ($Model_Data->isEmpty()) {
                Flash::error($this->msg_not_found);
                return redirect(route('restaurants.index'));
            }

            $order_ids = $Model_Data->pluck('o_id');
            $Order_Details = OrderDetail::leftJoin('items', 'order_details.item_id', '=', 'items.id')
                    ->whereIn('order_details.order_id', $order_ids)
                    ->select(['order_details.id', 'order_details.order_id', 'order_details.quantity', 'order_details.item_value',
                        'order_details.discount', 'order_details.total_value', 'order_details.created_at', 'items.name as item_name'])
                    ->orderBy('order_details.order_id', 'asc')
                    ->get();

            $order_count = $Model_Data->count();

            return view($this->views_path . '.restaurant_orders', compact('Model_Data', 'Order_Details', 'order_count'));
        }
        else {
            Flash::error($this->view_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    private function getOrdersQuery() {
        return Order::leftJoin('restaurants', 'orders.rest_id', '=', 'restaurants.id')
                        ->leftJoin('app_users', 'orders.user_id', '=', 'app_users.id')
                        ->leftJoin('serve_tables', 'orders.table_id', '=', 'serve_tables.id')
                        ->select(['orders.id', 'orders.order_no', 'orders.rest_id', 'orders.table_id', 'orders.final_value', 'orders.user_id', 'orders.status', 'restaurants.name as rest_name', 'app_users.name as user_name', 'serve_tables.title as table_name']);
    }

    private function applyFilters($query, Request $request) {
        $requestParams = ['order_no', 'user_id', 'table_id', 'order_value', 'status'];
        foreach ($requestParams as $param) {
            if ($request->has($param) && $request->get($param) != -1 && !empty($request->get($param))) {
                $query->where('orders.' . $param, $request->get($param));
            }
        }
    }

    private function renderOrderNo($Records) {
        $title = $Records->order_no;
        if (Auth::user()->can('orders-view') || Auth::user()->can('all')) {
            $title = '<a href="' . route('orders.show', $Records->id) . '" title="View Order Details">' . $title . '</a>';
        }
        return $title;
    }

    private function renderStatus($Records) {
        $statusMap = [
            3 => 'Confirmed', 4 => 'Declined', 5 => 'Accepted',
            6 => 'Preparing', 7 => 'Ready', 8 => 'Picked/Served', 9 => 'Paid'
        ];
        return $statusMap[$Records->status] ?? 'Unknown';
    }

    private function renderActions($Records) {
        $str = "<div class='btn-group' role='group'>";
        if (Auth::user()->can($this->view_permission) || Auth::user()->can('all')) {
            $str .= '<a class="btn btn-outline-primary" href="' . route($this->view_route, $Records->id) . '" title="View Details"><i class="fa fa-eye"></i></a>';
        }
        $str .= "</div>";
        return $str;
    }

    public function datatable(Request $request) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->list_permission) || $Auth_User->can('all')) {
            if ($Auth_User->rest_id == 0) {
                return $this->admin_datatable($request);
            }
            return $this->restaurant_datatable($request);
        }
        Flash::error($this->list_permission_error_message);
        return redirect()->route($this->redirect_home);
    }

    private function admin_datatable(Request $request) {
        $Records = $this->getOrdersQuery()
                ->where('orders.status', '>=', 3)
                ->orderBy('orders.order_no', 'desc');

        $this->applyFilters($Records, $request);

        return Datatables::of($Records)
                        ->addColumn('rest_id', fn($Records) => $Records->rest_name)
                        ->addColumn('order_no', fn($Records) => $this->renderOrderNo($Records))
                        ->addColumn('user_id', fn($Records) => $Records->user_name)
                        ->addColumn('table_id', fn($Records) => $Records->table_name)
                        ->addColumn('order_value', fn($Records) => $Records->final_value)
                        ->addColumn('status', fn($Records) => $this->renderStatus($Records))
                        ->addColumn('approval', function ($Records) {
                            // Add conditional approval logic here if needed
                            return $this->renderStatus($Records);
                        })
                        ->addColumn('action', fn($Records) => $this->renderActions($Records))
                        ->rawColumns(['rest_id', 'order_no', 'user_id', 'table_id', 'order_value', 'status', 'approval', 'action'])
                        ->setRowId(fn($Records) => 'myDtRow' . $Records->id)
                        ->make(true);
    }

    private function restaurant_datatable(Request $request) {
        $rest_id = Auth::user()->rest_id;
        $Records = $this->getOrdersQuery()
                ->where('orders.rest_id', '=', $rest_id)
                ->where('orders.status', '>=', 3)
                ->orderBy('orders.order_no', 'desc');

        $this->applyFilters($Records, $request);

        return Datatables::of($Records)
                        ->addColumn('order_no', fn($Records) => $this->renderOrderNo($Records))
                        ->addColumn('user_id', fn($Records) => $Records->user_name)
                        ->addColumn('table_id', fn($Records) => $Records->table_name)
                        ->addColumn('order_value', fn($Records) => $Records->final_value)
                        ->addColumn('status', fn($Records) => $this->renderStatus($Records))
                        ->addColumn('action', fn($Records) => $this->renderActions($Records))
                        ->rawColumns(['order_no', 'user_id', 'table_id', 'order_value', 'status', 'action'])
                        ->setRowId(fn($Records) => 'myDtRow' . $Records->id)
                        ->make(true);
    }

    public function dashboard_datatable(Request $request) {
        $rest_id = Auth::user()->rest_id;
        $created_at = Carbon::today();
        $Records = $this->getOrdersQuery()
                ->where('orders.rest_id', $rest_id)
                ->where('orders.status', '>=', 3)
                ->whereDate('orders.created_at', '>=',  $created_at)
                ->orderBy('orders.order_no', 'desc');

        $this->applyFilters($Records, $request);

        return Datatables::of($Records)
                        ->addColumn('order_no', fn($Records) => $this->renderOrderNo($Records))
                        ->addColumn('user_id', fn($Records) => $Records->user_name)
                        ->addColumn('table_id', fn($Records) => $Records->table_name)
                        ->addColumn('order_value', fn($Records) => $Records->final_value)
                        ->addColumn('status', fn($Records) => $this->renderStatus($Records))
                        ->addColumn('action', fn($Records) => $this->renderActions($Records))
                        ->rawColumns(['order_no', 'user_id', 'table_id', 'order_value', 'status', 'action'])
                        ->setRowId(fn($Records) => 'myDtRow' . $Records->id)
                        ->make(true);
    }

    public function create() {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->add_permission) || $Auth_User->can('all')) {
            return redirect()->route($this->home_route);
        }
        else {
            Flash::error($this->add_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function store(CreateOrderRequest $request) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->add_permission) || $Auth_User->can('all')) {
            //
        }
        else {
            Flash::error($this->add_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function show($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $Model_Data = Order::leftJoin('restaurants', 'orders.rest_id', '=', 'restaurants.id')
                    ->leftJoin('app_users', 'orders.user_id', '=', 'app_users.id')
                    ->select(['orders.*', 'orders.id as o_id', 'orders.rest_id as r_id', 'orders.user_arrived',
                        'app_users.id as u_id', 'app_users.name as u_name', 'app_users.phone as u_phone',
                        'app_users.email as u_email', 'app_users.username as user_name'])
                    ->where('orders.id', '=', $id)
                    ->where('orders.status', '>=', 3)
                    ->first();

            if (!$Model_Data) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }

            $data = Order::find($Model_Data->o_id);
            $orders_common_details = $this->get_order_details_common($data);
            $order_count = Order::where(['user_id' => $Model_Data->u_id])->count();

            $Order_Details = OrderDetail::leftJoin('items', 'order_details.item_id', '=', 'items.id')
                    ->select(['order_details.id', 'order_details.order_id', 'order_details.quantity', 'order_details.item_value',
                        'order_details.discount', 'order_details.total_value', 'order_details.created_at', 'items.name as item_name'])
                    ->where(['order_details.order_id' => $id])
                    ->get();

            return view($this->views_path . '.show', compact('Model_Data', 'Order_Details', 'orders_common_details', 'order_count'));
        }
        else {
            Flash::error($this->view_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function edit($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            //
        }
        else {
            Flash::error($this->edit_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function update($id, UpdateOrderRequest $request) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            //
        }
        else {
            Flash::error($this->edit_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function destroy($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->delete_permission) || $Auth_User->can('all')) {
            //
        }
        else {
            Flash::error($this->delete_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function makeInActive($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Order::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }
            $Model_Data->status = 0;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();
            Flash::success('Order Deactivated successfully.');
            return redirect(route($this->home_route));
        }
        else {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function makeActive($id) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $Model_Data = Order::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }
            $Model_Data->status = 1;
            $Model_Data->updated_by = $Auth_User->id;
            $Model_Data->save();
            Flash::success('Order Activated successfully.');
            return redirect(route($this->home_route));
        }
        else {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function is_not_authorized($id, $Auth_User) {
        $bool = 1;
        if ($Auth_User->rest_id != 0) {
            $rest_id = $Auth_User->rest_id;
            $records = Order::select(['id'])->where('id', '=', $id)->where('rest_id', '=', $rest_id)->limit(1)->get();
            if ($records->isNotEmpty()) {
                $bool = 0;
            }
        }
        return $bool;
    }

    public function statuschange(Request $request) {
        $Auth_User = Auth::user();
        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $id = $request->order_id;
            $Model_Data = Order::find($id);
            if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
                Flash::error($this->msg_not_found);
                return redirect(route($this->home_route));
            }
            $Model_Data->status = $request->status_change;
            $Model_Data->update();
            $this->generateInvoice($id);
            Flash::success('Status Updated successfully.');
            return redirect(route($this->home_route));
        }
        else {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }
    }

    public function makePDF($id) {
        update_order_status_automatically();

        $pdf_data = $this->orders_generate_pdf_data($id);

        if ($pdf_data) {
            $pdf_data['pdf_link'] = url('storage/app/invoices/invoice-' . $id . '.pdf');
            $pdf = PDF::loadView('myPdf', $pdf_data);
            Storage::put('invoices/invoice-' . $id . '.pdf', $pdf->output());
            return $pdf->download('invoice-' . $id . '.pdf');
        }

        return redirect(route($this->home_route));
    }
}
