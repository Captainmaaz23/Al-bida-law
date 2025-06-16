<?php

namespace App\Http\Controllers;

use App\Models\Services;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables as Datatables;


class LawSController extends Controller
{
    private $msg_created = "Item added successfully.";
    protected $list_permission = 'service-list'; // <-- change value to match your permission
    protected $list_permission_error_message = 'You do not have permission to view services.';
    protected $redirect_home = 'law-services.'; 
    protected $redirect_edit = 'law-services.'; 
    private $view_permission = "law-service.show";
    private $edit_permission = "law-service.edit";
    private $status_permission = "law-service-status";
    private $msg_updated = "Service updated successfully.";
    private $msg_deleted = "Service deleted successfully.";
    private $msg_not_found = "Service not found. Please try again.";
    /**
     * Display a listing of the resource.
     */
    public function index($list_type = 'All') {
        return $this->commonListing($list_type);
    }   

    private function commonListing($list_type) {
        if (!Auth::user()->can($this->list_permission)) {
            Flash::error($this->list_permission_error_message);
            return redirect()->route('services.index'); // Adjust route if needed
        }
    
        // If this is not required for Services, you can remove it
        items_availability_automatically(); 
    
        $filter_array = ['-1'=> 'All', '1' => 'Active', '0' => 'Inactive'];
        $filter_status = -1;
        $list_title = 'Services';
        $recordsExists = 0;
    
        switch($list_type) {
            case 'active':
                $filter_status = 1;
                $recordsExists = Services::where('status', 1)->exists(); // TRUE if active records exist
                $list_title = 'Active '.$list_title;
                break;
    
            case 'inactive':
                $filter_status = 0;
                $recordsExists = Services::where('status', 0)->exists(); // TRUE if inactive records exist
                $list_title = 'Inactive '.$list_title;
                break;
    
            default:
                $recordsExists = Services::exists(); // TRUE if any record exists
                $list_title = 'All '.$list_title;
                break;            
        }
    
        return view('restaurants.services.index', compact("recordsExists", "list_title", "filter_status", "filter_array"));
    }
    
    

    public function datatable(Request $request) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->list_permission) || $Auth_User->can('all')) {
            return $Auth_User->rest_id == 0 ? $this->service_datatable($request) : $this->restaurant_datatable($request);
        }

        Flash::error($this->list_permission_error_message);
        return redirect()->route($this->redirect_home.'index');
    }


    // public function service_datatable(Request $request)
    // {
    //     $Records = Services::select(['id', 'name', 'description']);

    //     $response = Datatables::of($Records)
    //         ->filter(function ($query) use ($request) {
    //             if ($request->has('name') && !is_null($request->name)) {
    //                 $query->where('name', 'like', "%{$request->name}%");
    //             }

    //             if ($request->has('description') && !is_null($request->description)) {
    //                 $query->where('description', 'like', "%{$request->description}%");
    //             }
    //         })
    //         ->addColumn('name', fn($record) => $record->name)
    //         ->addColumn('description', fn($record) => $record->description)
    //         ->addColumn('action', function ($record) {
    //             // Replace with your actual action buttons logic
    //             return '<a href="' . route('law-services.edit', $record->id) . '" class="btn btn-sm btn-primary">Edit</a>';
    //         })
    //         ->rawColumns(['name', 'description', 'action'])
    //         ->setRowId(fn($record) => 'myDtRow' . $record->id)
    //         ->make(true);

    //     return $response;
    // }


    
    public function service_datatable(Request $request)
    {
        $Records = Services::select(['id', 'name', 'description', 'image','status']);
    
        $response = Datatables::of($Records)
            ->filter(function ($query) use ($request) {
                if ($request->has('name') && !is_null($request->name)) {
                    $query->where('name', 'like', "%{$request->name}%");
                }
    
                if ($request->has('description') && !is_null($request->description)) {
                    $query->where('description', 'like', "%{$request->description}%");
                }
            })
            ->addColumn('name', fn($record) => $record->name)
            ->addColumn('description', function ($record) {
                $words = explode(' ', $record->description);
                if (count($words) > 8) {
                    $truncated = implode(' ', array_slice($words, 0, 8)) . '...';
                } else {
                    $truncated = $record->description;
                }
                return $truncated;
            })
            
            ->addColumn('image', function ($record) {
                if ($record->image) {
                    $imageUrl = url('public/uploads/services/' . $record->image); // Use url() instead of asset()
                    return '<img src="' . $imageUrl . '" width="50" height="50" />';
                } else {
                    return 'No Image';
                }
            })
            
            
            
            ->addColumn('action', function ($Records) {
                return $this->getActionButtons($Records);
            })
            ->addColumn('status', function ($Records) {
                return $this->getStatusDropdown($Records);
            })
            ->rawColumns(['name', 'description', 'image', 'action','status'])
            ->setRowId(fn($record) => 'myDtRow' . $record->id)
            ->make(true);
    
        return $response;
    }

    public function getActionButtons($record) {
        $action_buttons = "<div class='btn-group' role='group' aria-label='Horizontal Info'>";
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('law-services.show', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-eye"></i></a>';
        }

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('law-services.edit', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>';
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
            $route = $status == 1 ? route('service-inactive', $record_id) : route('service-active', $record_id);

            return '<a href="' . $route . '" class="btn ' . $buttonClass . ' btn-sm" title="' . $title . '"><span class="fa fa-power-off"></span></a>';
        }

        return $status == 1 ? '<a href="javascript:void(0)" class="btn btn-success btn-sm"><span class="fa fa-power-off "></span></a>' : '<a href="javascript:void(0)" class="btn btn-danger btn-sm"><span class="fa fa-power-off"></span></a>';
    }

    public function makeActive($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = Services::find($id);

        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect()->back();
        }

        Services::where('id', $id)->update(['status' => 1, 'updated_by' => $Auth_User->id]);

        Flash::success('Service Activated successfully.');
        return redirect()->back();
    }

    public function makeInActive($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = Services::find($id);

        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect()->back();
        }

        Services::where('id', $id)->update(['status' => 0, 'updated_by' => $Auth_User->id]);

        Flash::success('Service Deactivated successfully.');
        return redirect()->back();
    }

    private function is_not_authorized($record_id, $Auth_User) {
        $record = Services::find($record_id);
        
        // Adjust this check according to your logic (example: if user owns the record)
        return $record->created_by !== $Auth_User->id;
    }
    
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('restaurants.services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $service = new Services();
        $service->name = $request->name;
        $service->description = $request->description;
        $service->created_by = Auth::id();
        $service->title_arabic = $request->title_arabic;
        $service->arabic_description = $request->arabic_description;
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('uploads/services');
            $image->move($path, $imageName);
            $service->image = $imageName;
        }
        Flash::success($this->msg_created);
        $service->save(); 
           
        return redirect()->route($this->redirect_home.'index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $services = Services::find($id);
        // return $services;
        return view('restaurants.services.show',compact('services'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $services = Services::find($id);
        return view('restaurants.services.edit',compact('services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $services = Services::find($id);
        $services->name = $request->name;
        $services->title_arabic = $request->title_arabic;
        $services->description = $request->description;
        $services->arabic_description = $request->arabic_description;
        if ($request->hasFile('image')) {
            $validatedData = $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
            ]);
            if ($services->image && file_exists(public_path('uploads/services/' . $services->image))) {
                unlink(public_path('uploads/services/' . $services->image));
            }
        
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension(); 
            $image->move(public_path('uploads/services'), $imageName);
            $services->image = $imageName;

        }
        $services->save();
        return redirect()->route('law-services.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
