<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseController;
use App\Http\Resources\Contact as ContactResource;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class ContactApiController extends BaseController {

    public function index(Request $request) {
        $title = $request->input('title', '');

        if (!empty($title)) {
            return $this->show($title);
        }

        return $this->index_2();
    }

    public function show($title) {
        $modelData = GeneralSetting::where('title', 'like', '%' . $title . '%')->where('status', 1)->get();

        if ($modelData->isEmpty()) {
            return $this->sendError('Contact not found.');
        }

        return $this->sendResponse(ContactResource::collection($modelData), 'Contacts retrieved successfully.');
    }

    public function index_2() {
        $modelData = GeneralSetting::where('status', 1)->get();

        if ($modelData->isEmpty()) {
            return $this->sendError('Contacts not found.');
        }

        return $this->sendResponse(ContactResource::collection($modelData), 'Contacts retrieved successfully.');
    }
}
