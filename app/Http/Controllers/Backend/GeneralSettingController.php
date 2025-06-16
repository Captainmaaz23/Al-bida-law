<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\MainController as MainController;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables as Datatables;
use Flash;
use Response;
use Auth;

class GeneralSettingController extends MainController {
    private $uploads_path = "uploads/audios";
    private $views_path = "general_settings";
    private $home_route = "general-settings.index";
    private $msg_created = "General Setting added successfully.";
    private $msg_updated = "General Setting updated successfully.";
    private $msg_exists = "Record Already Exists with same Contact name";
    private $msg_not_found = "General Setting not found. Please try again."; 

    public function index() {
        if (!$this->userCan('general-settings-listing')) {
            Flash::error("Error: You are not authorized to view listings.");
            return redirect()->route($this->redirect_home);
        }

        $recordsExists = GeneralSetting::exists();
        $ramadan = GeneralSetting::find(8);
        return view($this->views_path . '.listing', compact("recordsExists", "ramadan"));
    }

    public function datatable(Request $request) {
        if (!$this->userCan('general-settings-listing')) {
            Flash::error("Error: You are not authorized to view listings.");
            return redirect()->route($this->redirect_home);
        }

        $records = GeneralSetting::query()
            ->where('id', '<>', 8)
            ->select(['id', 'title', 'value', 'status']);

        return Datatables::of($records)
            ->filter(function ($query) use ($request) {
                if ($request->has('title') && !empty($request->title)) {
                    $query->where('title', 'like', "%{$request->get('title')}%");
                }
                if ($request->has('s_value') && !empty($request->s_value)) {
                    $query->where('value', 'like', "%{$request->get('s_value')}%");
                }
                if ($request->has('status') && $request->get('status') != -1) {
                    $query->where('status', $request->get('status'));
                }
            })
            ->addColumn('title', function ($record) {
                return $this->generateTitleColumn($record);
            })
            ->addColumn('status', function ($record) {
                return $this->generateStatusColumn($record);
            })
            ->addColumn('action', function ($record) {
                return $this->generateActionColumn($record);
            })
            ->rawColumns(['title', 'status', 'action'])
            ->setRowId(fn($record) => 'myDtRow' . $record->id)
            ->make(true);
    }

    private function generateTitleColumn($record) {
        $title = $record->title;
        if ($this->userCan('general-settings-view')) {
            return '<a href="' . route('general-settings.show', $record->id) . '" title="View Details">' . $title . '</a>';
        }
        return $title;
    }

    private function generateStatusColumn($record) {
        $status = $record->status;
        $btnClass = $status ? 'btn-success' : 'btn-danger';
        $title = $status ? 'Make Inactive' : 'Make Active';
        $route = $status ? 'general_settings-deactivate' : 'general_settings-activate';
        
        if ($this->userCan('general-settings-status')) {
            return '<a href="' . route($route, $record->id) . '" class="btn ' . $btnClass . ' btn-sm" title="' . $title . '">
                        <span class="fa fa-power-off"></span>
                    </a>';
        }
        return '<span class="fa fa-power-off"></span>';
    }

    private function generateActionColumn($record) {
        $actions = "<div class='btn-group' role='group' aria-label='Actions'>";
        if ($this->userCan('general-settings-view')) {
            $actions .= '<a class="btn btn-outline-primary" href="' . route('general-settings.show', $record->id) . '" title="View Details"><i class="fa fa-eye"></i></a>';
        }
        if ($this->userCan('general-settings-edit')) {
            $actions .= '<a class="btn btn-outline-primary" href="' . route('general-settings.edit', $record->id) . '" title="Edit Details"><i class="fa fa-edit"></i></a>';
        }
        $actions .= "</div>";
        return $actions;
    }

    public function create() {
        if (!$this->userCan('general-settings-add')) {
            Flash::error("Error: You are not authorized to add General Setting.");
            return redirect()->route($this->home_route);
        }
        return view($this->views_path . '.create');
    }
    
    public function store(Request $request) {

        return redirect()->route($this->home_route);
        if (!$this->userCan('general-settings-add')) {
            Flash::error("Error: You are not authorized to add General Setting.");
            return redirect()->route($this->home_route);
        }

        $request->validate(['title' => 'required', 'value' => 'required']);

        $name = ltrim(rtrim($request->title));

        if ($name != '') {

            $bool = 0;

            $Model_Results = GeneralSetting::where('title', '=', $name)->get();

            foreach ($Model_Results as $Model_Result) {

                $bool = 1;
            }

            if ($bool == 0) {

                $Model_Data = new GeneralSetting();

                $Model_Data->title = $name;

                $Model_Data->value = ltrim(rtrim($request->value));

                $Model_Data->created_by = Auth::user()->id;

                $Model_Data->save();

                Flash::success($this->msg_created);

                return redirect()->route($this->home_route);
            }
            else {

                Flash::error($this->msg_exists);

                return redirect()->route($this->create_route);
            }
        }
        else {

            Flash::error($this->msg_required);

            return redirect()->route($this->create_route);
        }
    }
    
    public function show($id) {
        if (!$this->userCan('general-settings-view')) {
            Flash::error("Error: You are not authorized to view General Setting details.");
            return redirect()->route($this->home_route);
        }

        $modelData = GeneralSetting::findOrFail($id);
        return view($this->views_path . '.show', compact('modelData'));
    }
    
    public function edit() {
        if (!$this->userCan('general-settings-edit')) {
            Flash::error("Error: You are not authorized to edit General Setting.");
            return redirect()->route($this->home_route);
        }

        $Model_Data_1 = GeneralSetting::find(1);
        $Model_Data_2 = GeneralSetting::find(2);
        $Model_Data_3 = GeneralSetting::find(3);
        $Model_Data_4 = GeneralSetting::find(4);
        $Model_Data_5 = GeneralSetting::find(5);
        $Model_Data_11 = GeneralSetting::find(11);

        return view($this->views_path . '.edit', compact("Model_Data_1", "Model_Data_2", "Model_Data_3", "Model_Data_4", "Model_Data_5", "Model_Data_11"));
    }
    
    public function update(Request $request) {
        if (!$this->userCan('general-settings-edit')) {
            Flash::error("Error: You are not authorized to edit General Setting.");
            return redirect()->route($this->home_route);
        }
        
        $Model_Data = GeneralSetting::find(1);
        $Model_Data->title = 'Phone Number';
        $Model_Data->value = ltrim(rtrim($request->field_1));
        $Model_Data->updated_by = Auth::user()->id;
        $Model_Data->save();

        $Model_Data = GeneralSetting::find(2);
        $Model_Data->title = 'Email';
        $Model_Data->value = ltrim(rtrim($request->field_2));
        $Model_Data->updated_by = Auth::user()->id;
        $Model_Data->save();

        $Model_Data = GeneralSetting::find(3);
        $Model_Data->title = 'Website';
        $Model_Data->value = ltrim(rtrim($request->field_3));
        $Model_Data->updated_by = Auth::user()->id;
        $Model_Data->save();

        $Model_Data = GeneralSetting::find(4);
        $Model_Data->title = 'GST';
        $Model_Data->value = ltrim(rtrim($request->field_4));
        $Model_Data->updated_by = Auth::user()->id;
        $Model_Data->save();

        $Model_Data = GeneralSetting::find(5);
        $Model_Data->title = 'Service Charges';
        $Model_Data->value = ltrim(rtrim($request->field_5));
        $Model_Data->updated_by = Auth::user()->id;
        $Model_Data->save();

        $audio = '';

        if (isset($request->field_11) && $request->field_11 != null) {

            $file_uploaded = $request->file('field_11');

            $audio = date('YmdHis') . "." . $file_uploaded->getClientOriginalExtension();

            $uploads_path = $this->uploads_path;

            if (!is_dir($uploads_path)) {

                mkdir($uploads_path);

                $uploads_root = $this->_UPLOADS_ROOT;

                $src_file = $uploads_root . "/index.html";

                $dest_file = $uploads_path . "/index.html";

                copy($src_file, $dest_file);
            }



            $file_uploaded->move($this->uploads_path, $audio);

            $Model_Data = GeneralSetting::find(11);
            $Model_Data->title = 'New Order Notification Audio';
            $Model_Data->value = ltrim(rtrim($audio));
            $Model_Data->updated_by = Auth::user()->id;
            $Model_Data->save();
        }

        Flash::success($this->msg_updated);
        return redirect(route($this->home_route));
    }

    public function destroy($id) {
        if (!$this->userCan('general-settings-delete')) {
            Flash::error("Error: You are not authorized to delete General Setting.");
            return redirect()->route($this->home_route);
        }

        $modelData = GeneralSetting::findOrFail($id);
        $modelData->delete();
        
        Flash::success($this->msg_deleted);
        return redirect()->route($this->home_route);
    }

    public function startRamadan() {
        if (!$this->userCan('general-settings-edit')) {
            Flash::error("Error: You are not authorized to edit General Setting.");
            return redirect()->route($this->home_route);
        }

        $modelData = GeneralSetting::findOrFail(8);
        $modelData->update(['value' => 1, 'updated_by' => Auth::id()]);

        Flash::success('Ramadan started successfully.');
        return redirect()->route($this->home_route);
    }

    public function endRamadan() {
        if (!$this->userCan('general-settings-edit')) {
            Flash::error("Error: You are not authorized to edit General Setting.");
            return redirect()->route($this->home_route);
        }

        $modelData = GeneralSetting::findOrFail(8);
        $modelData->update(['value' => 0, 'updated_by' => Auth::id()]);

        Flash::success('Ramadan ended successfully.');
        return redirect()->route($this->home_route);
    }
}
