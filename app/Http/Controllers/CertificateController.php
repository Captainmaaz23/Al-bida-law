<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables as Datatables;

class CertificateController extends Controller
{
    private   $msg_created = "Question added successfully.";
    protected $list_permission = 'question-list'; 
    protected $list_permission_error_message = 'You do not have permission to view Question.';
    protected $redirect_home = 'general_settings.slidder.'; 
    protected $redirect_edit = 'general_settings.slidder.'; 
    private   $view_permission = "general_settings.slidder.show";
    private   $edit_permission = "general_settings.slidder.edit";
    private   $status_permission = "slidder-status";
    private   $msg_updated = "Question updated successfully.";
    private   $msg_deleted = "Question deleted successfully.";
    private   $msg_not_found = "Question not found. Please try again.";
    public function index($list_type = 'All')
    {
        return $this->commonListing($list_type);
    }

    private function commonListing($list_type) {
        if (!Auth::user()->can($this->list_permission)) {
            Flash::error($this->list_permission_error_message);
            return redirect()->route('founder.index'); // Adjust route if needed
        }
        // If this is not required for Services, you can remove it
        items_availability_automatically(); 
    
        $filter_array = ['-1'=> 'All', '1' => 'Active', '0' => 'Inactive'];
        $filter_status = -1;
        $list_title = 'Case Study';
        $recordsExists = 0;
    
        switch($list_type) {
            case 'active':
                $filter_status = 1;
                $recordsExists = Certificate::where('status', 1)->exists(); // TRUE if active records exist
                $list_title = 'Active '.$list_title;
                break;
    
            case 'inactive':
                $filter_status = 0;
                $recordsExists = Certificate::where('status', 0)->exists(); // TRUE if inactive records exist
                $list_title = 'Inactive '.$list_title;
                break;
    
            default:
                $recordsExists = Certificate::exists(); // TRUE if any record exists
                $list_title = 'All '.$list_title;
                break;            
        }
    
        return view('general_settings.certificates.index', compact("recordsExists", "list_title", "filter_status", "filter_array"));
    }

    public function datatable(Request $request) {
        $Auth_User = Auth::user();
        return $Auth_User->rest_id == 0 ? $this->service_datatable($request) : '';

        if ($Auth_User->can($this->list_permission) || $Auth_User->can('all')) {
            return $Auth_User->rest_id == 0 ? $this->service_datatable($request) : '';
        }

        Flash::error($this->list_permission_error_message);
        return redirect()->route('founder.index');
    }

    public function service_datatable(Request $request)
    {
        $Records =  Certificate::select(['id', 'title','image']);
    
        $response = Datatables::of($Records)
            ->filter(function ($query) use ($request) {
                if ($request->has('title') && !is_null($request->title)) {
                    $query->where('title', 'like', "%{$request->title}%");
                }
            })   
            ->addColumn('title', fn($record) => $record->title)         
            ->addColumn('image', function ($record) {
                if ($record->image) {
                    $imageUrl = url('public/uploads/certificates/' . $record->image); // Use url() instead of asset()
                    return '<img src="' . $imageUrl . '" width="50" height="50" />';
                } else {
                    return 'No Image';
                }
            })
            
            

            ->addColumn('action', function ($Records) {
                return $this->getActionButtons($Records);
            })
            ->rawColumns(['title','image','action'])
            ->setRowId(fn($record) => 'myDtRow' . $record->id)
            ->make(true);
        return $response;
    }

    public function getActionButtons($record) {
        $action_buttons = "<div class='btn-group' role='group' aria-label='Horizontal Info'>";
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('certificate.show', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-eye"></i></a>';
        }

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('certificate.edit', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>';
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
            $route = $status == 1 ? route('about-active', $record_id) : route('about-inactive', $record_id);

            return '<a href="' . $route . '" class="btn ' . $buttonClass . ' btn-sm" title="' . $title . '"><span class="fa fa-power-off"></span></a>';
        }

        return $status == 1 ? '<a href="javascript:void(0)" class="btn btn-success btn-sm"><span class="fa fa-power-off "></span></a>' : '<a href="javascript:void(0)" class="btn btn-danger btn-sm"><span class="fa fa-power-off"></span></a>';
    }

    public function makeActive($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route('certificates.index');
        }

        $Model_Data = About::find($id);
        
        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect()->back();
        }

        About::where('id', $id)->update(['status' => 1, 'updated_by' => $Auth_User->id]);

        Flash::success('Status Activated successfully.');
        return redirect()->back();
    }

    public function makeInActive($id) {
        $Auth_User = Auth::user();
        
        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route('certificates.index');
        }

        $Model_Data = About::find($id);
        
        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect()->back();
        }

        Certificate::where('id', $id)->update(['status' => 0, 'updated_by' => $Auth_User->id]);
        Flash::success('Status Deactivated successfully.');
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('general_settings.certificates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd ($request);
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image'       => 'required',
            'status'      => 'nullable|boolean',
        ]);
    
        $relativePath = null;
    
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('/uploads/certificates');
            $file->move($uploadDir, $fileName);
            $relativePath = $fileName;
        }
    
        $model = new Certificate();
        $model->arabic_title = $request->arabic_title;
        $model->arabic_description = $request->arabic_description;
        $model->fill($data);
        $model->image = $relativePath;
        $model->created_by = Auth::id();
        $model->updated_by = Auth::id();
        $model->save();
    
        return redirect()->route('certificate.index');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $certificate = Certificate::find($id);
        return view('general_settings.certificates.show',compact('certificate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $certificate = Certificate::findOrFail($id);
        return view('general_settings.certificates.edit',compact('certificate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $model = Certificate::findOrFail($id);
    
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($model->image && file_exists(public_path('uploads/certificates/' . $model->image))) {
                unlink(public_path('uploads/certificates/' . $model->image));
            }
    
            // Upload new image
            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/certificates'), $fileName);
            $data['image'] = $fileName;
        } else {
            unset($data['image']); // Prevent overwriting existing image with null
        }
    
        $model->fill($data);
        $model->updated_by = Auth::id(); // track the user who updated
        $model->arabic_title = $request->arabic_title;
        $model->arabic_description = $request->arabic_description;
        $model->save();
    
        return redirect()->route('certificate.index')->with('success', 'Certificate updated successfully.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
