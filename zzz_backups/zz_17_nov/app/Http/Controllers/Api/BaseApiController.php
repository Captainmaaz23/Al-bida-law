<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\MainController as MainController;
use Illuminate\Support\Facades\DB;
use App\Models\AuthKey;
use App\Models\AppUser;

class BaseApiController extends MainController {

    public function getKey() {
        // return $_key;
    }

    protected function base_authentication($request, $action) {
        ((!empty($request->header('token'))) ? $this->_TOKEN=$request->header('token') : $this->_TOKEN=NULL);
		
        ((!empty($request->header('lat'))) ? $this->_LATITUDE=$request->header('lat') : $this->_LATITUDE=NULL);
        ((!empty($request->header('lng'))) ? $this->_LONGITUDE=$request->header('lng') : $this->_LONGITUDE=NULL);
        ((!empty($request->header('radius'))) ? $this->_RADIUS=$request->header('radius') : $this->_RADIUS=$this->_RADIUS);

        $array = array();
        $array['status'] = FALSE;
        $array['message'] = 'You are not authorized.';
        $array['message_type'] = FALSE;

        $token = $this->_TOKEN;
        if (empty($token) || $token == NULL) {
            $array['message'] = 'Session is not active. Please Login again';
        }
        else {
            $Verifications = DB::table('verification_codes')->where('token', $token)->where('expired', 1)->first();
            if (empty($Verifications)) {
                $array['message'] = 'Incorrect Token!';
            }
            elseif ($action == 'update-location-data') {
                $this->update_app_user_location();
                $array['message'] = 'Updated User Location data successfully!';
                $array['message_type'] = TRUE;
            }
            elseif ($action == 'token-validate') {
                $array['message'] = 'Valid Token!';
                $array['message_type'] = TRUE;
            }
            elseif ($action == 'token-expire') {
                $this->expire_session($Verifications->user_id);
                $array['message'] = 'Session Expired Successfully!';
                $array['message_type'] = TRUE;
            }
            else {
                $user_id = $Verifications->user_id;
                $User = AppUser::find($user_id);

                if (empty($User)) {
                    $this->expire_session($user_id);
                    $array['message'] = 'User Not Found!';
                }
                elseif ($User->status == $this->_IN_ACTIVE) {
                    $this->expire_session($user_id);
                    $array['message'] = 'Your Account is Inactive/Suspended by Admin.';
                }
                else {
                    $this->update_app_user_location();                   

                    $this->_PAGINATION_START = ((int)($request->page ?? NULL) > 0) ? (int)$request->page : $this->_PAGINATION_START;
                    $this->_PAGINATION_LIMIT = ((int)($request->limit ?? NULL) > 0) ? (int)$request->limit : $this->_PAGINATION_START;
                    
                    $this->_USER = $User;
                    $this->_USER_ID = $User->id;
                    $this->_USER_TYPE = $User->user_type;

                    $array['status'] = TRUE;
                    $array['message'] = 'You are authorized.';
                }
            }
        }
        return $array;
    }

    protected function update_app_user_location() {
        if (!empty($this->_TOKEN) && !empty($this->_LATITUDE) && !empty($this->_LONGITUDE)) {
            update_app_user_location_data($this->_TOKEN, $this->_LATITUDE, $this->_LONGITUDE);
        }
    }

    protected function expire_session($user_id = 0) {
        DB::table('verification_codes')->where('user_id', $user_id)->update([
            'expired'    => 0,
            'updated_at' => now()
        ]);
                
        /*$user_id = ($user_id == 0) ? $this->_User_Id : $user_id;
        $auth_key = $this->_TOKEN;
        $auth_key = time() . '-Expired';

        DB::table('auth_keys')->where('user_id', $user_id)->where('auth_key', $auth_key)->update([
            'auth_key' => $auth_key
        ]);*/
    }

    public function sendResponse($result, $message) {
        $response = [
            'code'    => '201',
            'status'  => true,
            'message' => $message,
            'data'    => $result
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $result = NULL, $code = 404) {
        $response = [
            'code'    => '101',
            'status'  => false,
            'message' => $error,
            'data'    => $result
        ];

        return response()->json($response, $code);
    }

    public function sendSuccess($message, $result = NULL, $code = 200) {
        $response = [
            'code'    => '201',
            'status'  => true,
            'message' => $message,
            'data'    => $result
        ];

        return response()->json($response, $code);
    }

    public function convertExceptionToArray(Exception $e, $response = FALSE) {
        if (!config('app.debug')) {
            $statusCode = $e->getStatusCode();

            switch ($statusCode) {
                case 401:
                    $response['responseText'] = 'Unauthorized';
                    break;

                case 403:
                    $response['responseText'] = 'Forbidden';
                    break;

                case 404:
                    $response['responseText'] = 'Resource Not Found';
                    break;

                case 405:
                    $response['responseText'] = 'Method Not Allowed';
                    break;

                case 422:
                    $response['responseText'] = 'Request unable to be processed';
                    break;

                default:
                    $response['responseText'] = ($statusCode == 500) ? 'Whoops, looks like something went wrong' : $e->getMessage();
                    break;
            }
        }

        return parent::convertExceptionToArray($e, $response);
    }
}
