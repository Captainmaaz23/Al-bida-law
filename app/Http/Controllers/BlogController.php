<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables as Datatables;

class BlogController extends Controller
{

    private $msg_created = "Item added successfully.";
    protected $list_permission = 'blog-post'; // <-- change value to match your permission
    protected $list_permission_error_message = 'You do not have permission to view Blogs.';
    protected $redirect_home = 'blog-post.'; 
    protected $redirect_edit = 'blog-post.'; 
    private $view_permission = "blog-post.show";
    private $edit_permission = "blog-post.edit";
    private $status_permission = "blog-post-status";
    private $msg_updated = "Blog updated successfully.";
    private $msg_deleted = "Blog deleted successfully.";
    private $msg_not_found = "Blog not found. Please try again.";

    /**
     * Display a listing of the resource.
     */
    public function index($list_type = 'All')
    {
        // dd ($this->commonListing($list_type));
        return $this->commonListing($list_type);
    }

    private function commonListing($list_type) {
        if (!Auth::user()->can($this->list_permission)) {
            Flash::error($this->list_permission_error_message);
            return redirect()->route('blog-post.index'); 
        }
    
        // items_availability_automatically(); 
    
        $filter_array = ['-1'=> 'All', '1' => 'Active', '0' => 'Inactive'];
        $filter_status = -1;
        $list_title = 'Blogs';
        $recordsExists = 0;
    
        switch($list_type) {
            case 'active':
                $filter_status = 1;
                $recordsExists = Blogs::where('status', 1)->exists(); // TRUE if active records exist
                $list_title = 'Active '.$list_title;
                break;
    
            case 'inactive':
                $filter_status = 0;
                $recordsExists = Blogs::where('status', 0)->exists(); // TRUE if inactive records exist
                $list_title = 'Inactive '.$list_title;
                break;
    
            default:
                $recordsExists = Blogs::exists(); // TRUE if any record exists
                $list_title = 'All '.$list_title;
                break;            
        }
    
        return view('restaurants.blogs.index', compact("recordsExists", "list_title", "filter_status", "filter_array"));
    }

    public function datatable(Request $request) {
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->list_permission) || $Auth_User->can('all')) {
            return $Auth_User->rest_id == 0 ? $this->service_datatable($request) : '';
        }

        Flash::error($this->list_permission_error_message);
        return redirect()->route($this->redirect_home.'index');
    }

    public function service_datatable(Request $request)
    {
        $Records = Blogs::select(['id', 'name', 'description', 'image','status']);
    
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
                return count($words) > 8
                    ? implode(' ', array_slice($words, 0, 8)) . '...'
                    : $record->description;
            })
            ->addColumn('image', function ($record) {
                if ($record->image) {
                    $imageUrl = url('public/uploads/blogs/' . $record->image);
                    return '<img src="' . $imageUrl . '" width="50" height="50" />';
                } else {
                    return 'No Image';
                }
            })
            ->addColumn('action', function ($record) {
                return $this->getActionButtons($record);
            })
            ->addColumn('status', function ($record) {
                return $this->getStatusDropdown($record);
            })
            ->rawColumns(['name', 'description', 'image', 'action', 'status'])
            ->setRowId(fn($record) => 'myDtRow' . $record->id)
            ->make(true);
    
        return $response;
    }
    

    public function getActionButtons($record) {
        $action_buttons = "<div class='btn-group' role='group' aria-label='Horizontal Info'>";
        $Auth_User = Auth::user();

        if ($Auth_User->can($this->view_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('blog-post.show', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-eye"></i></a>';
        }

        if ($Auth_User->can($this->edit_permission) || $Auth_User->can('all')) {
            $action_buttons .= '<a href="' . route('blog-post.edit', $record->id) . '" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>';
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
            $route = $status == 1 ? route('blog-inactive', $record_id) : route('blog-active', $record_id);

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

        $Model_Data = Blogs::find($id);

        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect()->back();
        }

        Blogs::where('id', $id)->update(['status' => 1, 'updated_by' => $Auth_User->id]);

        Flash::success('Blog Activated successfully.');
        return redirect()->back();
    }

    public function makeInActive($id) {
        $Auth_User = Auth::user();

        if (!$Auth_User->can($this->status_permission) && !$Auth_User->can('all')) {
            Flash::error($this->status_permission_error_message);
            return redirect()->route($this->home_route);
        }

        $Model_Data = Blogs::find($id);

        if (empty($Model_Data) || $this->is_not_authorized($id, $Auth_User)) {
            Flash::error($this->msg_not_found);
            return redirect()->back();
        }

        Blogs::where('id', $id)->update(['status' => 0, 'updated_by' => $Auth_User->id]);

        Flash::success('Blog Deactivated successfully.');
        return redirect()->back();
    }

    private function is_not_authorized($record_id, $Auth_User) {
        $record = Blogs::find($record_id);
        
        // Adjust this check according to your logic (example: if user owns the record)
        return $record->created_by !== $Auth_User->id;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('restaurants.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $blogs = new Blogs();
        $blogs->name = $request->name;
        $blogs->description = $request->description;
        $blogs->created_by = Auth::id();
        $blogs->arabic_title = $request->arabic_title;
        $blogs->arabic_description = $request->arabic_description;
        $blogs->tag = $request->tag;
        $blogs->arabic_tag = $request->arabic_tag;
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('uploads/blogs');
            $image->move($path, $imageName);
            $blogs->image = $imageName;
        }
        Flash::success($this->msg_created);
        $blogs->save(); 
           
        return redirect()->route('blog-post.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blog = Blogs::find($id);
        return view('restaurants.blogs.show',compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $blog = Blogs::find($id);
        return view('restaurants.blogs.edit',compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // return $request;
        $blog = Blogs::findOrFail($id);
    
        $blog->name = $request->name;
        $blog->description = $request->description;
        $blog->arabic_title = $request->arabic_title;
        $blog->arabic_description = $request->arabic_description;
        $blog->tag = $request->tag;
        $blog->arabic_tag = $request->arabic_tag;

        if ($request->hasFile('image')) {
            $oldImagePath = public_path('uploads/blogs/' . $blog->image);
            if ($blog->image && file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
    
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/blogs'), $imageName);
            $blog->image = $imageName;
        }
    
        $blog->save();
    
        Flash::success($this->msg_updated ?? 'Blog updated successfully.');
        return redirect()->route('blog-post.index');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
