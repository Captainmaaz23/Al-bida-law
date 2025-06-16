<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables as Datatables;

class QuestionController extends Controller
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
            return redirect()->route('question.index'); // Adjust route if needed
        }
        // If this is not required for Services, you can remove it
        items_availability_automatically(); 
    
        $filter_array = ['-1'=> 'All', '1' => 'Active', '0' => 'Inactive'];
        $filter_status = -1;
        $list_title = 'Questions';
        $recordsExists = 0;
    
        switch($list_type) {
            case 'active':
                $filter_status = 1;
                $recordsExists = Question::where('status', 1)->exists(); // TRUE if active records exist
                $list_title = 'Active '.$list_title;
                break;
    
            case 'inactive':
                $filter_status = 0;
                $recordsExists = Question::where('status', 0)->exists(); // TRUE if inactive records exist
                $list_title = 'Inactive '.$list_title;
                break;
    
            default:
                $recordsExists = Question::exists(); // TRUE if any record exists
                $list_title = 'All '.$list_title;
                break;            
        }
    
        return view('restaurants.questions.index', compact("recordsExists", "list_title", "filter_status", "filter_array"));
    }

    public function datatable(Request $request) {
        $Auth_User = Auth::user();
        return $Auth_User->rest_id == 0 ? $this->service_datatable($request) : '';

        if ($Auth_User->can($this->list_permission) || $Auth_User->can('all')) {
            return $Auth_User->rest_id == 0 ? $this->service_datatable($request) : '';
        }

        Flash::error($this->list_permission_error_message);
        return redirect()->route('question.index');
    }

    public function service_datatable(Request $request)
    {
        $Records = Question::select(['id', 'english_question','status']);
    
        $response = Datatables::of($Records)
            ->filter(function ($query) use ($request) {
                if ($request->has('english_question') && !is_null($request->english_question)) {
                    $query->where('english_question', 'like', "%{$request->english_question}%");
                }
            })            
            ->addColumn('english_question', fn($record) => $record->english_question)
            ->addColumn('action', function ($Records) {
                return $this->getActionButtons($Records);
            })
            ->addColumn('status', function ($Records) {
                return $this->getStatusDropdown($Records);
            })
            ->rawColumns(['english_question','action','status'])
            ->setRowId(fn($record) => 'myDtRow' . $record->id)
            ->make(true);
        return $response;
    }

    public function getActionButtons($record) {
        $action_buttons = "<div class='btn-group' role='group' aria-label='Horizontal Info'>";
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('question.show', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-eye"></i></a>';
        }

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('question.edit', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>';
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
            $route = $status == 1 ? route('question-active', $record_id) : route('question-inactive', $record_id);

            return '<a href="' . $route . '" class="btn ' . $buttonClass . ' btn-sm" title="' . $title . '"><span class="fa fa-power-off"></span></a>';
        }

        return $status == 1 ? '<a href="javascript:void(0)" class="btn btn-success btn-sm"><span class="fa fa-power-off "></span></a>' : '<a href="javascript:void(0)" class="btn btn-danger btn-sm"><span class="fa fa-power-off"></span></a>';
    }

    public function makeActive($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route('question.index');
        }

        $Model_Data = Question::find($id);
        
        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect()->back();
        }

        Question::where('id', $id)->update(['status' => 1, 'updated_by' => $Auth_User->id]);

        Flash::success('Status Activated successfully.');
        return redirect()->back();
    }

    public function makeInActive($id) {
        $Auth_User = Auth::user();
        
        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route('question.index');
        }

        $Model_Data = Question::find($id);
        
        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect()->back();
        }

        Question::where('id', $id)->update(['status' => 0, 'updated_by' => $Auth_User->id]);
        Flash::success('Status Deactivated successfully.');
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('restaurants.questions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $question = new Question();
        $question->english_question = $request->english_question;
        $question->arabic_question = $request->arabic_question;
        $question->english_description = $request->english_description;
        $question->arabic_description = $request->arabic_description;
        $question->created_by = Auth::id();
    
       $question->save();
    
        return redirect()->route('question.index')->with('success', 'Question created successfully.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $question = Question::find($id);
        return view('restaurants.questions.show',compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $question = Question::find($id);
        return view("restaurants.questions.edit",compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $question = Question::find($id);
        $question->english_question = $request->english_question;
        $question->arabic_question = $request->arabic_question;
        $question->english_description = $request->english_description;
        $question->arabic_description = $request->arabic_description;
        $question->updated_by = Auth::id();
    
       $question->save();
    
        return redirect()->route('question.index')->with('success', 'Question Updated successfully.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
