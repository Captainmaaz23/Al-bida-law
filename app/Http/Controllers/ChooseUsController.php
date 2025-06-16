<?php

namespace App\Http\Controllers;

use App\Models\ChooseUs;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Models\ChooseUsDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables as Datatables;

class ChooseUsController extends Controller
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
            return redirect()->route('chooseus.index'); // Adjust route if needed
        }
    
        // If this is not required for Services, you can remove it
        items_availability_automatically(); 
    
        $filter_array = ['-1'=> 'All', '1' => 'Active', '0' => 'Inactive'];
        $filter_status = -1;
        $list_title = 'Choose';
        $recordsExists = 0;
    
        switch($list_type) {
            case 'active':
                $filter_status = 1;
                $recordsExists = ChooseUs::where('status', 1)->exists(); // TRUE if active records exist
                $list_title = 'Active '.$list_title;
                break;
    
            case 'inactive':
                $filter_status = 0;
                $recordsExists = ChooseUs::where('status', 0)->exists(); // TRUE if inactive records exist
                $list_title = 'Inactive '.$list_title;
                break;
    
            default:
                $recordsExists = ChooseUs::exists(); // TRUE if any record exists
                $list_title = 'All '.$list_title;
                break;            
        }
    
        return view('general_settings.why-choose-us.index', compact("recordsExists", "list_title", "filter_status", "filter_array"));
    }

    public function datatable(Request $request) {
        $Auth_User = Auth::user();
        return $Auth_User->rest_id == 0 ? $this->service_datatable($request) : '';

        if ($Auth_User->can($this->list_permission) || $Auth_User->can('all')) {
            return $Auth_User->rest_id == 0 ? $this->service_datatable($request) : '';
        }

        Flash::error($this->list_permission_error_message);
        return redirect()->route('chooseus.index');
    }

    public function service_datatable(Request $request)
    {
        $Records = ChooseUs::select(['id', 'image','heading']);
    
        $response = Datatables::of($Records)
        ->filter(function ($query) use ($request) {
            if ($request->has('heading') && !is_null($request->heading)) {
                $query->where('heading', 'like', "%{$request->heading}%");
            }
        })            
        ->addColumn('heading', fn($record) => $record->heading)

            ->addColumn('image', function ($record) {
                try {
                    if ($record->image) {
                        $imageUrl = url('public/uploads/why-choose/image/' . $record->image);
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
            ->rawColumns(['heading','image','action'])
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
            $action_buttons .= '<a href="' . route('chooseus.edit', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>';
        }
        $action_buttons .= "</div></center>";

        return $action_buttons;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $chooseUs = ChooseUs::with('details')->findOrFail($id);   
        return view('general_settings.why-choose-us.edit', compact('chooseUs'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $choose = ChooseUs::findOrFail($id);
        $choose->heading = $request->heading;
        $choose->summary = $request->summary;
    
        // Handle main image update
        if ($request->hasFile('image')) {
            if ($choose->image) {
                $oldImagePath = public_path('uploads/why-choose/image/' . $choose->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
    
            $mainImage = $request->file('image');
            $mainImageName = time() . '_' . $mainImage->getClientOriginalName();
            $mainImage->move(public_path('uploads/why-choose/image'), $mainImageName);
            $choose->image = $mainImageName;
        }
    
        $choose->save();
    
        // Handle sub fields
        if ($request->has('sub_heading')) {
            foreach ($request->sub_heading as $key => $subHeading) {
                $subSummary = $request->sub_summary[$key] ?? null;
                $subImage = $request->file('sub_image')[$key] ?? null;
                $detailId = $request->detail_ids[$key] ?? null;
    
                $subImageName = null;
    
                if ($detailId) {
                    // Update existing
                    $detail = ChooseUsDetail::find($detailId);
                    if ($detail) {
                        if ($subImage) {
                            // Delete old sub image
                            if ($detail->sub_image) {
                                $oldSubImgPath = public_path('uploads/why-choose/sub_image/' . $detail->sub_image);
                                if (File::exists($oldSubImgPath)) {
                                    File::delete($oldSubImgPath);
                                }
                            }
    
                            $subImageName = time() . '_' . $subImage->getClientOriginalName();
                            $subImage->move(public_path('uploads/why-choose/sub_image'), $subImageName);
                            $detail->sub_image = $subImageName;
                        }
    
                        $detail->sub_heading = $subHeading;
                        $detail->sub_summary = $subSummary;
                        $detail->save();
                    }
                } else {
                    // Create new
                    if ($subImage) {
                        $subImageName = time() . '_' . $subImage->getClientOriginalName();
                        $subImage->move(public_path('uploads/why-choose/sub_image'), $subImageName);
                    }
    
                    ChooseUsDetail::create([
                        'choose_us_id' => $choose->id,
                        'sub_heading' => $subHeading,
                        'sub_summary' => $subSummary,
                        'sub_image' => $subImageName,
                    ]);
                }
            }
        }
    
        return redirect()->route('chooseus.index')->with('success', 'Choose Us section updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function removefield($id) {
        $dlete_record = ChooseUsDetail::find($id);
        if ($dlete_record) {
            $dlete_record->delete();
            return response()->json([
                'success' => true,
                'message' => 'Record Delete Successfully'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ], 404);
        }
    }
    

}


