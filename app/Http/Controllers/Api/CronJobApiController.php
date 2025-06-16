<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseController;
use Illuminate\Http\Request;

class CronJobApiController extends BaseController {

    public function index(Request $request, $action = 'listing') {
        if ($action === 'listing') {
            return $this->listing();
        }
    }

    public function listing() {
        update_order_status_automatically();
        serve_tables_availability_automatically();
        menus_availability_automatically();
        items_availability_automatically();
    }
}
