<?php

namespace App\Http\Controllers;

use App\Models\OurTeam;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables as Datatables;

class TeamController extends Controller
{
    private   $msg_created = "Member added successfully.";
    protected $list_permission = 'slidder-list'; 
    protected $list_permission_error_message = 'You do not have permission to view services.';
    protected $redirect_home = 'general_settings.slidder.'; 
    protected $redirect_edit = 'general_settings.slidder.'; 
    private   $view_permission = "general_settings.slidder.show";
    private   $edit_permission = "general_settings.slidder.edit";
    private   $status_permission = "team-status";
    private   $msg_updated = "Member updated successfully.";
    private   $msg_deleted = "Member deleted successfully.";
    private   $msg_not_found = "Member not found. Please try again.";
    public function index($list_type = 'All')
    {
        return $this->commonListing($list_type);
    }

    private function commonListing($list_type) {
        if (!Auth::user()->can($this->list_permission)) {
            Flash::error($this->list_permission_error_message);
            return redirect()->route('our-team.index'); // Adjust route if needed
        }
    
        // If this is not required for Services, you can remove it
        items_availability_automatically(); 
    
        $filter_array = ['-1'=> 'All', '1' => 'Active', '0' => 'Inactive'];
        $filter_status = -1;
        $list_title = 'Our Team';
        $recordsExists = 0;
    
        switch($list_type) {
            case 'active':
                $filter_status = 1;
                $recordsExists = OurTeam::where('status', 1)->exists(); // TRUE if active records exist
                $list_title = 'Active '.$list_title;
                break;
    
            case 'inactive':
                $filter_status = 0;
                $recordsExists = OurTeam::where('status', 0)->exists(); // TRUE if inactive records exist
                $list_title = 'Inactive '.$list_title;
                break;
    
            default:
                $recordsExists = OurTeam::exists(); // TRUE if any record exists
                $list_title = 'All '.$list_title;
                break;            
        }
    
        return view('app_users.our-teams.index', compact("recordsExists", "list_title", "filter_status", "filter_array"));
    }

    public function datatable(Request $request) {
        $Auth_User = Auth::user();
        return $Auth_User->rest_id == 0 ? $this->service_datatable($request) : '';

        if ($Auth_User->can($this->list_permission) || $Auth_User->can('all')) {
            return $Auth_User->rest_id == 0 ? $this->service_datatable($request) : '';
        }

        Flash::error($this->list_permission_error_message);
        return redirect()->route('our-team.index');
    }

    public function service_datatable(Request $request)
    {
        $Records = OurTeam::select(['id', 'name', 'image','title','status']);
    
        $response = Datatables::of($Records)
            ->filter(function ($query) use ($request) {
                if ($request->has('name') && !is_null($request->name)) {
                    $query->where('name', 'like', "%{$request->name}%");
                }
            })
            ->addColumn('image', function ($record) {
                try {
                    if ($record->image) {
                        $imageUrl = url('public/uploads/our-teams/' . $record->image);
                        return '<img src="' . $imageUrl . '" width="50" height="50" />';
                    } else {
                        return 'No Image';
                    }
                } catch (\Exception $e) {
                    return 'Error';
                }
            })
            
            ->addColumn('name', fn($record) => $record->name)
            ->addColumn('title', fn($record) => $record->title)
            ->addColumn('action', function ($Records) {
                return $this->getActionButtons($Records);
            })
            ->addColumn('status', function ($Records) {
                return $this->getStatusDropdown($Records);
            })
            ->rawColumns([ 'name', 'title','image','action','status'])
            ->setRowId(fn($record) => 'myDtRow' . $record->id)
            ->make(true);
        return $response;
    }

    public function getActionButtons($record) {
        $action_buttons = "<div class='btn-group' role='group' aria-label='Horizontal Info'>";
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('our-team.show', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-eye"></i></a>';
        }

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('our-team.edit', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>';
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
            $route = $status == 1 ? route('our-team-active', $record_id) : route('our-team-inactive', $record_id);

            return '<a href="' . $route . '" class="btn ' . $buttonClass . ' btn-sm" title="' . $title . '"><span class="fa fa-power-off"></span></a>';
        }

        return $status == 1 ? '<a href="javascript:void(0)" class="btn btn-success btn-sm"><span class="fa fa-power-off "></span></a>' : '<a href="javascript:void(0)" class="btn btn-danger btn-sm"><span class="fa fa-power-off"></span></a>';
    }   

    public function makeActive($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route('our-team.index');
        }

        $Model_Data = OurTeam::find($id);
        // dd($Model_Data);
        
        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect()->back();
        }

        OurTeam::where('id', $id)->update(['status' => 1, 'updated_by' => $Auth_User->id]);

        Flash::success('Status Activated successfully.');
        return redirect()->back();
    }

    public function makeInActive($id) {
        $Auth_User = Auth::user();
        
        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route('our-team.index');
        }

        $Model_Data = OurTeam::find($id);
        
        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect()->back();
        }

        OurTeam::where('id', $id)->update(['status' => 0, 'updated_by' => $Auth_User->id]);
        Flash::success('Status Deactivated successfully.');
        return redirect()->back();
    }

        private function is_not_authorized($record_id, $Auth_User) {
        $record = OurTeam::find($record_id);
        return $record->created_by !== $Auth_User->id;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('app_users.our-teams.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);
    
        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/our-teams');   
                $image->move($destinationPath, $imageName);
                $validatedData['image'] = $imageName;
            }
            $validatedData['created_by'] = Auth::id();
            OurTeam::create($validatedData);
            Flash::success('Team member added successfully.');
            return redirect()->route('our-team.index');
            
        } catch (\Exception $e) {
            \Log::error('Error creating team member: '.$e->getMessage());
            if (isset($imageName) && file_exists($destinationPath.'/'.$imageName)) {
                unlink($destinationPath.'/'.$imageName);
            }
            Flash::error('Error creating team member. Please try again.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $team = OurTeam::find($id);
        return view('app_users.our-teams.show',compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $team = OurTeam::find($id);
        return view('app_users.our-teams.edit',compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        try {
            $teamMember = OurTeam::findOrFail($id);
            $destinationPath = public_path('uploads/our-teams');
    
            // Handle image replacement if new image uploaded
            if ($request->hasFile('image')) {
                // Delete old image
                if ($teamMember->image && file_exists($destinationPath.'/'.$teamMember->image)) {
                    unlink($destinationPath.'/'.$teamMember->image);
                }
    
                // Upload new image
                $image = $request->file('image');
                $imageName = time().'.'.$image->getClientOriginalExtension();
                $image->move($destinationPath, $imageName);
                $validatedData['image'] = $imageName;
            }
    
            $validatedData['updated_by'] = Auth::id();
            $teamMember->update($validatedData);
    
            Flash::success('Team member updated successfully.');
            return redirect()->route('our-team.index');
        } catch (\Exception $e) {
            \Log::error('Error updating team member: '.$e->getMessage());
            if (isset($imageName) && file_exists($destinationPath.'/'.$imageName)) {
                unlink($destinationPath.'/'.$imageName);
            }
    
            Flash::error('Error updating team member. Please try again.');
            return redirect()->back();
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
