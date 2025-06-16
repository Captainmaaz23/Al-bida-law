<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\MainController as MainController;
use Auth;
use File;
use Flash;
use Yajra\DataTables\Facades\DataTables as Datatables;
use App\Models\Order;
use App\Models\AppUser;
use App\Models\Restaurant;
use App\Models\ServeTable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AppUserController extends MainController {

    private $uploads_path = "uploads/app_users";
    private $views_path = "app_users";
    private $home_route = "app-users.index";
    private $create_route = "app-users.create";
    private $edit_route = "app-users.edit";
    private $view_route = "app-users.show";
    private $delete_route = "app-users.destroy";
    private $msg_created = "App user added successfully.";
    private $msg_updated = "App user updated successfully.";
    private $msg_deleted = "App user deleted successfully.";
    private $msg_not_found = "App user not found. Please try again.";
    private $msg_required = "Please fill all required fields.";
    private $msg_exists = "Record Already Exists with same email";
    private $list_permission = "app-users-listing";
    private $add_permission = "app-users-add";
    private $edit_permission = "app-users-edit";
    private $view_permission = "app-users-view";
    private $status_permission = "app-users-status";
    private $delete_permission = "app-users-delete";
    private $list_permission_error_message = "Error: You are not authorized to View Listings of App users. Please Contact Administrator.";
    private $add_permission_error_message = "Error: You are not authorized to Add App user. Please Contact Administrator.";
    private $edit_permission_error_message = "Error: You are not authorized to Update App user. Please Contact Administrator.";
    private $view_permission_error_message = "Error: You are not authorized to View App user details. Please Contact Administrator.";
    private $status_permission_error_message = "Error: You are not authorized to change status of App user. Please Contact Administrator.";
    private $delete_permission_error_message = "Error: You are not authorized to Delete App user. Please Contact Administrator.";

    public function index($list_type = 'All') {
        return $this->commonListing($list_type);
    }

    public function inactive_listing($list_type = 'inactive') {
        return $this->commonListing($list_type);
    }

    public function active_listing($list_type = 'active') {
        return $this->commonListing($list_type);
    }

    private function commonListing($list_type) {
        if (!$this->userCan($this->list_permission)) {
            Flash::error($this->list_permission_error_message);
            return redirect()->route($this->redirect_home);
        }
        
        $filter_array = ['-1'=> 'All', '1' => 'Active', '0' => 'Inactive'];
        $filter_status = -1;
        $list_title = 'App Users';
        $recordsExists = 0;
        switch($list_type){
            case 'active':
                $filter_status = 1;
                $recordsExists = AppUser::where('status', $this->_ACTIVE)->exists();
                $list_title = 'Active '.$list_title;
                break;
            
            case 'inactive':
                $filter_status = 0;
                $recordsExists = AppUser::where('status', $this->_IN_ACTIVE)->exists();
                $list_title = 'InActive '.$list_title;
                break;
            
            default:
                $recordsExists = AppUser::exists();
                $list_title = 'All '.$list_title;
                break;            
        }
        $Tables = ServeTable::where('status', 1)->orderBy('title', 'asc')->pluck('title', 'id');

        return view($this->views_path . '.listing', compact("recordsExists", "list_title", "filter_status", "filter_array", "Tables"));
    }

    public function datatable(Request $request) {
        if (!$this->userCan($this->list_permission)) {
            Flash::error($this->list_permission_error_message);
            return redirect()->route($this->redirect_home);
        }

        $query = AppUser::select(['id', 'name', 'username', 'phone', 'status']);
        return Datatables::of($query)
                        ->filter(function ($query) use ($request) {
                            $fields = ['username', 'name', 'email', 'phone', 'status'];
                            foreach ($fields as $field) {
                                if ($request->filled($field) && $field != 'status') {
                                    $query->where("app_users.$field", 'like', "%{$request->$field}%");
                                }
                                elseif ($field == 'status' && $request->status != -1) {
                                    $query->where("app_users.$field", $request->status);
                                }
                            }
                        })
                        ->addColumn('status', function ($Records) {
                            return $this->renderStatusToggle($Records);
                        })
                        ->addColumn('action', function ($Records) {
                            return $this->renderUserActions($Records);
                        })
                        ->rawColumns(['status', 'action'])
                        ->make(true);
    }

    public function renderStatusToggle($record) {
        $isActive = $record->status == 1;
        $toggleRoute = $isActive ? route('app_users_toggleStatus', [$record->id, 0]) : route('app_users_toggleStatus', [$record->id, 1]);
        $btnClass = $isActive ? 'btn-success' : 'btn-danger';
        $iconClass = 'fa-power-off';

        if ($this->userCan($this->status_permission)) {
            return '<a href="' . $toggleRoute . '" class="btn ' . $btnClass . ' btn-sm" title="Toggle User Status">
                    <span class="fa ' . $iconClass . '"></span>
                </a>';
        }
        else {
            return '<span class="fa ' . $iconClass . '"></span>';
        }
    }

    public function renderUserActions($record) {
        $viewButton = $editButton = $deleteButton = '';
        $recordId = $record->id;

        // View Button
        if ($this->userCan($this->view_permission)) {
            $viewButton = '<a class="btn btn-outline-primary" href="' . route($this->view_route, $recordId) . '" title="View Details">
                        <i class="fa fa-eye"></i>
                      </a>';
        }

        // Edit Button
        if ($this->userCan($this->edit_permission)) {
            $editButton = '<a class="btn btn-outline-primary" href="' . route($this->edit_route, $recordId) . '" title="Edit Details">
                        <i class="fa fa-edit"></i>
                      </a>';
        }

        // Delete Button with Modal
        /*if ($this->userCan($this->delete_permission)) {
            $deleteButton = '<a class="btn btn-outline-warning" href="#" data-toggle="modal" data-target="#deleteModal-' . $recordId . '" title="Delete">
                            <i class="fa fa-trash"></i>
                         </a>
                         <div id="deleteModal-' . $recordId . '" class="modal fade" tabindex="-1" role="dialog">
                             <div class="modal-dialog" role="document">
                                 <div class="modal-content">
                                     <form action="' . route($this->delete_route, $recordId) . '" method="POST">
                                         <input type="hidden" name="_method" value="DELETE">
                                         <input type="hidden" name="_token" value="' . csrf_token() . '">
                                         <div class="modal-header">
                                             <h5 class="modal-title">Confirm Deletion</h5>
                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                 <span aria-hidden="true">&times;</span>
                                             </button>
                                         </div>
                                         <div class="modal-body">
                                             <p>Are you sure you want to delete this record?</p>
                                             <p><strong>' . $record->name . '</strong></p>
                                         </div>
                                         <div class="modal-footer">
                                             <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                             <button type="submit" class="btn btn-danger">Delete</button>
                                         </div>
                                     </form>
                                 </div>
                             </div>
                         </div>';
        }*/

        return '<div class="btn-group" role="group" aria-label="User Actions">' .
                $viewButton . $editButton . $deleteButton .
                '</div>';
    }

    public function create() {
        if (!$this->userCan($this->add_permission)) {
            Flash::error($this->add_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Tables = ServeTable::active()->orderBy('title')->pluck('title', 'id');
        return view("{$this->views_path}.create", compact("Tables"));
    }

    public function store(Request $request) {
        if (!$this->userCan($this->add_permission)) {
            Flash::error($this->add_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $validatedData = $request->validate([
            'name'        => 'required',
            'phone'       => 'required',
            'user_type'   => 'required',
            'username'    => 'required|unique:app_users,username',
            'password'    => 'required',
            'file_upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4048',
        ]);

        $validatedData['password'] = Hash::make($request->password);
        if ($request->user_type == 2) {
            $validatedData['table_id'] = $request->table_id;
        }

        if ($request->hasFile('file_upload')) {
            $image = '';
            if (isset($request->file_upload) && $request->file_upload != null)
            {
                    $file_uploaded = $request->file('file_upload');
                    $image = date('YmdHis').".".$file_uploaded->getClientOriginalExtension();

                    $uploads_path = $this->uploads_path;
                    if(!is_dir($uploads_path))
                    {
                            mkdir($uploads_path);
                            $uploads_root = $this->_UPLOADS_ROOT;
                            $src_file = $uploads_root."/index.html";
                            $dest_file = $uploads_path."/index.html";
                            copy($src_file,$dest_file);
                    }

                    $file_uploaded->move($this->uploads_path, $image); 
            }
            $validatedData['photo'] = $image;
        }
        unset($validatedData['file_upload']);

        AppUser::create($validatedData);
        Flash::success($this->msg_created);
        return redirect()->route($this->home_route);
    }

    private function uploadFile($file) {
        if (!$file) {
            return null;
        }

        $imageName = now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
        $file->move($this->uploads_path, $imageName);
        return $imageName;
    }

    public function order_datatable(Request $request, $id) {
        $Records = Order::leftJoin('restaurants', 'orders.rest_id', '=', 'restaurants.id')
                ->leftJoin('app_users', 'orders.user_id', '=', 'app_users.id')
                ->select([
                    'orders.id', 'orders.order_no', 'orders.rest_id', 'orders.pay_method',
                    'app_users.name as user_name', 'orders.order_value', 'orders.user_id',
                    'orders.status', 'restaurants.name as rest_name'
                ])
                ->where('orders.user_id', $id)
                ->where('orders.status', '>=', 3);

        $response = Datatables::of($Records)
                ->filter(function ($query) use ($request) {
                    $query
                    //->when($request->rest_id != -1, fn($q) => $q->where('orders.rest_id', $request->rest_id))
                    ->when($request->order_no, fn($q) => $q->where('orders.order_no', 'like', "%{$request->order_no}%"))
                    ->when($request->order_value, fn($q) => $q->where('orders.order_value', '>', $request->order_value))
                    ->when($request->order_status != -1, fn($q) => $q->where('orders.status', $request->order_status));
                })
                ->addColumn('rest_name', fn($Records) => $Records->rest_name)
                ->addColumn('order_no', fn($Records) => $this->orderLink($Records->id, $Records->order_no))
                ->addColumn('order_value', fn($Records) => $Records->order_value)
                ->addColumn('status', fn($Records) => $this->formatStatus($Records->status))
                ->addColumn('action', fn($Records) => $this->actionButtons($Records->id))
                ->rawColumns(['order_no', 'action'])
                ->setRowId(fn($Records) => 'myDtRow' . $Records->id)
                ->make(true);

        return $response;
    }

    private function orderLink($orderId, $orderNo) {
        if ($this->userCan('orders-view')) {
            return '<a href="' . route('orders.show', $orderId) . '" title="View Order Details">' . $orderNo . '</a>';
        }
        return $orderNo;
    }

    private function formatStatus($status) {
        return match ($status) {
            3 => 'Confirmed',
            4 => 'Declined',
            5 => 'Accepted',
            6 => 'Preparing',
            7 => 'Ready',
            8 => 'Picked',
            9 => 'Collected',
            default => $status,
        };
    }

    private function actionButtons($recordId) {
        return $this->userCan($this->view_permission) ? "<div class='btn-group' role='group'><a class='btn btn-outline-primary' href='" . route('orders.show', $recordId) . "' title='View Details' target='_blank'><i class='fa fa-eye'></i></a></div>" : '';
    }

    public function show(Request $request, $id) {
        if (!$this->userCan($this->view_permission)) {
            Flash::error($this->view_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = AppUser::find($id);
        if (!$Model_Data) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        $restaurants = Restaurant::active()->orderBy('name')->pluck('name', 'id');
        $UserOrders_exists = Order::where('user_id', $id)->exists();
        $Tables = ServeTable::active()->orderBy('title')->pluck('title', 'id');

        return view("{$this->views_path}.show", compact('Model_Data', 'restaurants', 'UserOrders_exists', 'Tables'));
    }

    public function edit($id) {
        if (!$this->userCan($this->edit_permission)) {
            Flash::error($this->edit_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = AppUser::find($id);
        if (!$Model_Data) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        $Tables = ServeTable::active()->orderBy('title')->pluck('title', 'id');
        return view("{$this->views_path}.edit", compact('Model_Data', 'Tables'));
    }

    public function update($id, Request $request) {
        if (!$this->userCan($this->edit_permission)) {
            Flash::error($this->edit_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $validated = $request->validate([
            'name'      => 'required',
            'phone'     => 'required',
            'user_type' => 'required',
        ]);

        $Model_Data = AppUser::find($id);
        if (!$Model_Data) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        $phoneExists = AppUser::where('id', '!=', $id)->where('phone', $validated['phone'])->exists();
        if ($phoneExists) {
            Flash::error($this->msg_exists);
            return redirect()->route($this->edit_route, $id);
        }

        $Model_Data->fill($validated);

        if ($request->filled('password')) {
            $Model_Data->password = Hash::make($request->password);
        }
        
        $image = $Model_Data->photo;
        $uploads_path = $this->uploads_path;
        if($request->hasfile('file_upload') && $request->file_upload != null)
        {	
            $file_uploaded = $request->file('file_upload');
            $image = date('YmdHis').".".$file_uploaded->getClientOriginalExtension();
            $file_uploaded->move($uploads_path, $image); 

            if ($Model_Data->photo != "") {
                File::delete($uploads_path."/".$Model_Data->photo);
            }
        }
        $Model_Data->photo = ltrim(rtrim($image));

        $Model_Data->save();
        Flash::success($this->msg_updated);
        return redirect(route($this->home_route));
    }

    public function destroy($id) {
        return redirect(route($this->home_route));
    }

    public function toggleStatus($id, $status) {
        if (!$this->userCan($this->status_permission)) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = AppUser::find($id);
        if (!$Model_Data) {
            Flash::error($this->msg_not_found);
            return redirect(route($this->home_route));
        }

        $Model_Data->status = $status;
        $Model_Data->updated_by = Auth::id();
        $Model_Data->save();

        $message = $status ? 'App User Activated successfully.' : 'App User Deactivated successfully.';
        Flash::success($message);
        return redirect(route($this->home_route));
    }
}
