<?php

namespace App\Http\Controllers;

use App\Models\Slidder;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables as Datatables;


class SlidderController extends Controller
{

    private   $msg_created = "Item added successfully.";
    protected $list_permission = 'slidder-list'; 
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
            return redirect()->route('general_settings.slidder.index'); // Adjust route if needed
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
                $recordsExists = Slidder::where('status', 1)->exists(); // TRUE if active records exist
                $list_title = 'Active '.$list_title;
                break;
    
            case 'inactive':
                $filter_status = 0;
                $recordsExists = Slidder::where('status', 0)->exists(); // TRUE if inactive records exist
                $list_title = 'Inactive '.$list_title;
                break;
    
            default:
                $recordsExists = Slidder::exists(); // TRUE if any record exists
                $list_title = 'All '.$list_title;
                break;            
        }
    
        return view('general_settings.slidder.index', compact("recordsExists", "list_title", "filter_status", "filter_array"));
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
        $Records = Slidder::select(['id', 'text', 'image','status']);
    
        $response = Datatables::of($Records)
            ->filter(function ($query) use ($request) {
                if ($request->has('text') && !is_null($request->text)) {
                    $query->where('text', 'like', "%{$request->text}%");
                }
            })
            ->addColumn('image', function ($record) {
                try {
                    if ($record->image) {
                        $imageUrl = url('public/uploads/slidder/' . $record->image);
                        return '<img src="' . $imageUrl . '" width="50" height="50" />';
                    } else {
                        return 'No Image';
                    }
                } catch (\Exception $e) {
                    return 'Error';
                }
            })
            
            ->addColumn('text', fn($record) => $record->text)
            ->addColumn('action', function ($Records) {
                return $this->getActionButtons($Records);
            })
            ->addColumn('status', function ($Records) {
                return $this->getStatusDropdown($Records);
            })
            ->rawColumns(['text', 'image', 'status','action'])
            ->setRowId(fn($record) => 'myDtRow' . $record->id)
            ->make(true);
        return $response;
    }

    public function getActionButtons($record) {
        $action_buttons = "<div class='btn-group' role='group' aria-label='Horizontal Info'>";
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('slidder.show', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-eye"></i></a>';
        }

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('slidder.edit', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>';
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
            $route = $status == 1 ? route('slidder-inactive', $record_id) : route('slidder-active', $record_id);

            return '<a href="' . $route . '" class="btn ' . $buttonClass . ' btn-sm" title="' . $title . '"><span class="fa fa-power-off"></span></a>';
        }

        return $status == 1 ? '<a href="javascript:void(0)" class="btn btn-success btn-sm"><span class="fa fa-power-off "></span></a>' : '<a href="javascript:void(0)" class="btn btn-danger btn-sm"><span class="fa fa-power-off"></span></a>';
    }

    public function makeActive($id) {
        $Auth_User = Auth::user();
        // return $Auth_User

        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route('slidder.index');
        }

        $Model_Data = Slidder::find($id);
        
        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect()->back();
        }

        Slidder::where('id', $id)->update(['status' => 1, 'updated_by' => $Auth_User->id]);

        Flash::success('Status Activated successfully.');
        return redirect()->back();
    }

    public function makeInActive($id) {
        $Auth_User = Auth::user();
        
        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route('slidder.index');
        }

        $Model_Data = Slidder::find($id);
        
        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect()->back();
        }

        Slidder::where('id', $id)->update(['status' => 0, 'updated_by' => $Auth_User->id]);
        Flash::success('Status Deactivated successfully.');
        return redirect()->back();
    }


    

    private function is_not_authorized($record_id, $Auth_User) {
        $record = Slidder::find($record_id);
        return $record->created_by !== $Auth_User->id;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('general_settings.slidder.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $slidder = new Slidder();
        $slidder->text = $request->text;
        $slidder->summary = $request->summary;
        $slidder->name = $request->name;
        $slidder->attorny = $request->attorny;
        $slidder->created_by = Auth::id();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('uploads/slidder');
            $image->move($path, $imageName);
            $slidder->image = $imageName;
        }
        Flash::success($this->msg_created);
        $slidder->save(); 
           
        return redirect()->route('slidder.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $slidder = Slidder::find($id);
        return view('general_settings.slidder.show',compact('slidder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $slidder = Slidder::find($id);
        return view('general_settings.slidder.edit',compact('slidder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $slidder = Slidder::findOrFail($id);
        $slidder->text = $request->text;
        $slidder->summary = $request->summary;
        $slidder->name = $request->name;
        $slidder->attorny = $request->attorny;
        $slidder->updated_by = Auth::id();

    if ($request->hasFile('image')) {
        if ($slidder->image && file_exists(public_path('uploads/slidder/' . $slidder->image))) {
            unlink(public_path('uploads/slidder/' . $slidder->image));
        }

        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $path = public_path('uploads/slidder');
        $image->move($path, $imageName);
        $slidder->image = $imageName;
    }

    $slidder->save();

    Flash::success($this->msg_updated); 

    return redirect()->route('slidder.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
