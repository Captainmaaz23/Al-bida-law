<?php

namespace App\Http\Controllers;

use App\Models\Slidder;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables as Datatables;
use App\Models\CompanyDetail;

class CompanyDetailController extends Controller
{
    private   $msg_created = "Item added successfully.";
    protected $list_permission = 'company-detail-list'; 
    protected $list_permission_error_message = 'You do not have permission to view services.';
    protected $redirect_home = 'general_settings.slidder.'; 
    protected $redirect_edit = 'general_settings.slidder.'; 
    private   $view_permission = "general_settings.slidder.show";
    private   $edit_permission = "general_settings.slidder.edit";
    private   $status_permission = "slidder-status";
    private   $msg_updated = "Service updated successfully.";
    private   $msg_deleted = "Service deleted successfully.";
    private   $msg_not_found = "Item not found. Please try again.";

    /**
     * Display a listing of the resource.
    */
    public function index($list_type = 'All')
    {
        return $this->commonListing($list_type);
    }

    private function commonListing($list_type) {
        if (!Auth::user()->can($this->list_permission)) {
            Flash::error($this->list_permission_error_message);
            return redirect()->route('company-detail.index'); // Adjust route if needed
        }
    
        // If this is not required for Services, you can remove it
        items_availability_automatically(); 
    
        $filter_array = ['-1'=> 'All', '1' => 'Active', '0' => 'Inactive'];
        $filter_status = -1;
        $list_title = 'Slidders';
        $recordsExists = 0;
    
        switch($list_type) {
            case 'active':
                $filter_status = 1;
                $recordsExists = CompanyDetail::where('status', 1)->exists(); // TRUE if active records exist
                $list_title = 'Active '.$list_title;
                break;
    
            case 'inactive':
                $filter_status = 0;
                $recordsExists = CompanyDetail::where('status', 0)->exists(); // TRUE if inactive records exist
                $list_title = 'Inactive '.$list_title;
                break;
    
            default:
                $recordsExists = CompanyDetail::exists(); // TRUE if any record exists
                $list_title = 'All '.$list_title;
                break;            
        }
    
        return view('general_settings.company-details.index', compact("recordsExists", "list_title", "filter_status", "filter_array"));
    }

    public function datatable(Request $request) {
        $Auth_User = Auth::user();
        return $Auth_User->rest_id == 0 ? $this->service_datatable($request) : '';

        if ($Auth_User->can($this->list_permission) || $Auth_User->can('all')) {
            return $Auth_User->rest_id == 0 ? $this->service_datatable($request) : '';
        }

        Flash::error($this->list_permission_error_message);
        return redirect()->route('general_settings.slidder.index');
    }

    public function service_datatable(Request $request)
    {
        $Records = CompanyDetail::select(['id','email', 'phonenumber', 'address','status']);
    
        $response = Datatables::of($Records)
            ->filter(function ($query) use ($request) {
                if ($request->has('email') && !is_null($request->email)) {
                    $query->where('email', 'like', "%{$request->email}%");
                }
            })
            
            ->addColumn('email', fn($record) => $record->email)
            ->addColumn('phonenumber', fn($record) => $record->phonenumber)
            ->addColumn('address', fn($record) => $record->address)
            ->addColumn('action', function ($Records) {
                return $this->getActionButtons($Records);
            })
            ->addColumn('status', function ($Records) {
                return $this->getStatusDropdown($Records);
            })
            ->rawColumns(['email', 'phonenumber', 'address','status','action'])
            ->setRowId(fn($record) => 'myDtRow' . $record->id)
            ->make(true);
        return $response;
    }

    public function getActionButtons($record) {
        $action_buttons = "<div class='btn-group' role='group' aria-label='Horizontal Info'>";
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('company-detail.show', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-eye"></i></a>';
        }

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('company-detail.edit', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>';
        }
        $action_buttons .= "</div>";

        return $action_buttons;
    }

    private function getStatusDropdown($Records) {
        $status = $Records->status;
        $record_id = $Records->id;
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->status_permission) || $Auth_User->can('all')) {
            $buttonClass = $status == 1 ? 'btn-success' : 'btn-danger';
            $title = $status == 1 ? 'Make Item Inactive' : 'Make Item Active';
            $route = $status == 1 ? route('company-detail-inactive', $record_id) : route('company-detail-active', $record_id);

            return '<a href="' . $route . '" class="btn ' . $buttonClass . ' btn-sm" title="' . $title . '"><span class="fa fa-power-off"></span></a>';
        }

        return $status == 1 ? '<a href="javascript:void(0)" class="btn btn-success btn-sm"><span class="fa fa-power-off "></span></a>' : '<a href="javascript:void(0)" class="btn btn-danger btn-sm"><span class="fa fa-power-off"></span></a>';
    }

    public function makeActive($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route('company-detail.index', $record->id);

        }

        $Model_Data = CompanyDetail::find($id);
        
        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect()->back();
        }

        CompanyDetail::where('id', $id)->update(['status' => 1, 'updated_by' => $Auth_User->id]);

        Flash::success('Status Activated successfully.');
        return redirect()->back();
    }

    public function makeInActive($id) {
        $Auth_User = Auth::user();
        
        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route('company-detail.index', $record->id);

        }

        $Model_Data = CompanyDetail::find($id);
        
        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect()->back();
        }

        CompanyDetail::where('id', $id)->update(['status' => 0, 'updated_by' => $Auth_User->id]);
        Flash::success('Status Deactivated successfully.');
        return redirect()->back();
    }


    

    private function is_not_authorized($record_id, $Auth_User) {
        $record = CompanyDetail::find($record_id);
        return $record->created_by !== $Auth_User->id;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('general_settings.company-details.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    

    $companydetail = new CompanyDetail();
    $companydetail->email = $request->email;
    $companydetail->phonenumber = $request->phonenumber;
    $companydetail->facebook = $request->facebook ?? null;
    $companydetail->snapchat = $request->snapchat ?? null;
    $companydetail->instagram = $request->instagram ?? null;
    $companydetail->twitter = $request->twitter ?? null;
    $companydetail->youtube = $request->youtube ?? null;
    $companydetail->address = $request->address;
    $companydetail->created_by = Auth::id();

    $companydetail->save();

    return redirect()->route('company-detail.index'); // adjust route name if needed
}

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $company = CompanyDetail::find($id);
        return view('general_settings.company-details.show',compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $companydetail = CompanyDetail::find($id);
        return view('general_settings.company-details.edit',compact('companydetail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $companydetail = CompanyDetail::find($id);
    $companydetail->email = $request->email;
    $companydetail->phonenumber = $request->phonenumber;
    $companydetail->facebook = $request->facebook ?? null;
    $companydetail->snapchat = $request->snapchat ?? null;
    $companydetail->instagram = $request->instagram ?? null;
    $companydetail->twitter = $request->twitter ?? null;
    $companydetail->youtube = $request->youtube ?? null;
    $companydetail->address = $request->address;
    $companydetail->updated_by = Auth::id();

    $companydetail->save();

    return redirect()->route('company-detail.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
