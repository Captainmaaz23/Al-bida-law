<?php

namespace App\Http\Controllers;

use App\Models\Logo;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables as Datatables;

class LogoController extends Controller
{

    private   $msg_created = "Logo added successfully.";
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
            return redirect()->route('web-logo.index'); // Adjust route if needed
        }
    
        // If this is not required for Services, you can remove it
        items_availability_automatically(); 
    
        $filter_array = ['-1'=> 'All', '1' => 'Active', '0' => 'Inactive'];
        $filter_status = -1;
        $list_title = 'Logo';
        $recordsExists = 0;
    
        switch($list_type) {
            case 'active':
                $filter_status = 1;
                $recordsExists = Logo::where('status', 1)->exists(); // TRUE if active records exist
                $list_title = 'Active '.$list_title;
                break;
    
            case 'inactive':
                $filter_status = 0;
                $recordsExists = Logo::where('status', 0)->exists(); // TRUE if inactive records exist
                $list_title = 'Inactive '.$list_title;
                break;
    
            default:
                $recordsExists = Logo::exists(); // TRUE if any record exists
                $list_title = 'All '.$list_title;
                break;            
        }
    
        return view('general_settings.logo.index', compact("recordsExists", "list_title", "filter_status", "filter_array"));
    }

    public function datatable(Request $request) {
        $Auth_User = Auth::user();
        return $Auth_User->rest_id == 0 ? $this->service_datatable($request) : '';

        if ($Auth_User->can($this->list_permission) || $Auth_User->can('all')) {
            return $Auth_User->rest_id == 0 ? $this->service_datatable($request) : '';
        }

        Flash::error($this->list_permission_error_message);
        return redirect()->route('web-logo.index');
    }

    public function service_datatable(Request $request)
    {
        $Records = Logo::select(['id', 'image']);
    
        $response = Datatables::of($Records)
            // ->filter(function ($query) use ($request) {
            //     if ($request->has('text') && !is_null($request->text)) {
            //         $query->where('text', 'like', "%{$request->text}%");
            //     }
            // })
            ->addColumn('image', function ($record) {
                try {
                    if ($record->image) {
                        $imageUrl = url('public/uploads/logo/' . $record->image);
                        return '<img src="' . $imageUrl . '" width="50" height="50" />';
                    } else {
                        return 'No Image';
                    }
                } catch (\Exception $e) {
                    return 'Error';
                }
            })
            
            ->addColumn('action', function ($Records) {
                return $this->getActionButtons($Records);
            })
            // ->addColumn('status', function ($Records) {
            //     return $this->getStatusDropdown($Records);
            // })
            ->rawColumns([ 'image','action'])
            ->setRowId(fn($record) => 'myDtRow' . $record->id)
            ->make(true);
        return $response;
    }

    public function getActionButtons($record) {
        $action_buttons = "<center><div class='btn-group justify-content-center item-center' role='group' aria-label='Horizontal Info'>";
        $Auth_User = Auth::user();

        // if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
        //     $action_buttons .= '<a href="' . route('web-logo.show', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-eye"></i></a>';
        // }

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('web-logo.edit', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>';
        }
        $action_buttons .= "</div></center>";

        return $action_buttons;
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('general_settings.logo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $logo = new Logo();
        $logo->created_by = Auth::id();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('uploads/logo');
            $image->move($path, $imageName);
            $logo->image = $imageName;
        }
        Flash::success($this->msg_created);
        $logo->save(); 
        return redirect()->route('web-logo.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $logo = Logo::find($id);
        return view('general_settings.logo.edit',compact('logo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $logo = Logo::find($id);
        $logo->updated_by = Auth::id();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('uploads/logo');
            $image->move($path, $imageName);
            $logo->image = $imageName;
        }
        Flash::success($this->msg_created);
        $logo->save(); 
        return redirect()->route('web-logo.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
