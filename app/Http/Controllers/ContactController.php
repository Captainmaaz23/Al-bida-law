<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Mail\ContactSubmittedMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables as Datatables;

class ContactController extends Controller
{
    private   $msg_created = "Contact added successfully.";
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
            return redirect()->route('contact.index'); // Adjust route if needed
        }
    
        // If this is not required for Services, you can remove it
        items_availability_automatically(); 
    
        $filter_array = ['-1'=> 'All', '1' => 'Active', '0' => 'Inactive'];
        $filter_status = -1;
        $list_title = 'Contact';
        $recordsExists = 0;
    
        switch($list_type) {
            case 'active':
                $filter_status = 1;
                $recordsExists = Contact::where('status', 1)->exists(); // TRUE if active records exist
                $list_title = 'Active '.$list_title;
                break;
    
            case 'inactive':
                $filter_status = 0;
                $recordsExists = C::where('status', 0)->exists(); // TRUE if inactive records exist
                $list_title = 'Inactive '.$list_title;
                break;
    
            default:
                $recordsExists = Contact::exists(); // TRUE if any record exists
                $list_title = 'All '.$list_title;
                break;            
        }
    
        return view('general_settings.contacts.index', compact("recordsExists", "list_title", "filter_status", "filter_array"));
    }

    public function datatable(Request $request) {
        $Auth_User = Auth::user();
        return $Auth_User->rest_id == 0 ? $this->service_datatable($request) : '';

        if ($Auth_User->can($this->list_permission) || $Auth_User->can('all')) {
            return $Auth_User->rest_id == 0 ? $this->service_datatable($request) : '';
        }

        Flash::error($this->list_permission_error_message);
        return redirect()->route('contact.index');
    }

    // public function service_datatable(Request $request)
    // {
    //     $Records = Contact::select(['id','fullname','email','phone','city','message']);
    
    //     $response = Datatables::of($Records)
    //     ->filter(function ($query) use ($request) {
    //         if ($request->has('text') && !is_null($request->text)) {
    //             $query->where('fullname', 'like', "%{$request->text}%"); // or whatever column
    //         }
    //     })
        
    //         ->addColumn('fullname', fn($record) => $record->fullname)
    //         ->addColumn('phone', fn($record) => $record->phone)
    //         ->addColumn('city', fn($record) => $record->city)
    //         ->addColumn('action', function ($Records) {
    //             return $this->getActionButtons($Records);
    //         })
    //         // ->addColumn('status', function ($Records) {
    //         //     return $this->getStatusDropdown($Records);
    //         // })
    //         ->rawColumns(['fullname','phone','city','action'])
    //         ->setRowId(fn($record) => 'myDtRow' . $record->id)
    //         ->make(true);
    //     return $response;
    // }

    public function service_datatable(Request $request)
{
    \Log::info('Request:', $request->all());

    $Records = Contact::select(['id', 'fullname', 'email', 'phone', 'city', 'message']);

    $response = Datatables::of($Records)
        ->filter(function ($query) use ($request) {
            if ($request->has('fullname') && !is_null($request->fullname)) {
                $query->where('fullname', 'like', "%{$request->fullname}%");
            }
        })
        ->addColumn('fullname', fn($record) => $record->fullname)
        ->addColumn('phone', fn($record) => $record->phone)
        ->addColumn('city', fn($record) => $record->city)
        ->addColumn('action', function ($record) {
            return $this->getActionButtons($record);
        })
        ->rawColumns(['fullname','phone','city','action'])
        ->setRowId(fn($record) => 'myDtRow' . $record->id)
        ->make(true);

    return $response;
}

    public function getActionButtons($record) {
        $action_buttons = "<center><div class='btn-group justify-content-center item-center' role='group' aria-label='Horizontal Info'>";
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('contact.show', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-eye"></i></a>';
        }

        // if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
        //     $action_buttons .= '<a href="' . route('contact.edit', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>';
        // }
        $action_buttons .= "</div></center>";

        return $action_buttons;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('general_settings.contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'phone'    => 'required|string|max:255',
            'city'     => 'nullable|string|max:255',
            'message'  => 'required|string',
            'date'     => 'required|string'
        ]);
    
        $contact = new Contact();
        $contact->fullname   = $validated['fullname'];
        $contact->email      = $validated['email'];
        $contact->phone      = $validated['phone'];
        $contact->city       = '';
        $contact->message    = $validated['message'];
        $contact->date = $request->date;
        $contact->created_by = Auth::id();
        $contact->status     = 1;
    
        $contact->save();
    
        // Send email to coachselector@gmail.com
        Mail::to('coachselector@gmail.com')->send(new ContactSubmittedMail($contact));
    
        return redirect()->back()->with('success', 'Contact submitted and email sent.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contact = Contact::find($id);
        return view('general_settings.contacts.show',compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
