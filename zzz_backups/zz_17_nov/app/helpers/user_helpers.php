<?php

use App\Models\AppUser;
use Illuminate\Support\Facades\DB;

if (!function_exists('get_user_array')) {

    function get_user_array($record) {
        $image = $record->photo;
        $image_path = '';

        if ($record->photo_type == 0) {
            $image_path = $image === 'app_user.png' ? 'defaults/' : 'app_users/';
            $image_path .= $image;
            $image_path = uploads($image_path);
        }
        else {
            $image_path .= $image;
        }

        $user_type_str = $record->user_type == 2 ? 'table_user' : 'waiter';
        $user = [
            'id'           => (int) $record->id,
            'user_type_id' => (int) $record->user_type,
            //'thw_client_id' => $record->thw_client_id,
            'user_type'    => $user_type_str,
            'username'     => $record->username,
            'name'         => $record->name,
            'phone'        => $record->phone,
            'email'        => $record->email,
            'photo'        => $image_path,
                //'balance' => $record->balance,
        ];
        if ($record->user_type == 2) {
            $user['table_id'] = (int) $record->table_id;
        }

        return $user;
    }

}

if (!function_exists('getUser')) {

    function getUser($token) {
        if (empty($token)) {
            return null;
        }

        $Verifications = DB::table('verification_codes')
                ->where('token', $token)
                ->where('expired', 1)
                ->first();

        return $Verifications ? AppUser::find($Verifications->user_id) : null;
    }

}

if (!function_exists('update_app_user_location_data')) {

    function update_app_user_location_data($token, $lat, $lng) {
        $User = getUser($token);

        if ($User) {
            $User->lat = $lat;
            $User->lng = $lng;
            $User->save();
        }
    }

}

if (!function_exists('app_user_data')) {

    function app_user_data($id) {
        return AppUser::where('id', $id)->first();
    }

}



if (!function_exists('get_user_div_for_kitchen')) {

    function get_user_div_for_kitchen($user_id, $created_at) {
        $record = AppUser::find($user_id);

        $image = $record->photo;
        $image_path = '';
        if ($record->photo_type == 0) {
            $image_path = 'app_users/';
            if ($image == 'app_user.png') {
                $image_path = 'defaults/';
            }
            $image_path .= $image;
            $image_path = uploads($image_path);
        }
        else {
            $image_path .= $image;
        }

        $user_name = ucwords($record->name);
        ?>
        <div class="usy-dt"> 
            <a href="#">
                <?php
                if ($image_path != '' && $image_path != 'app_user.png') {
                    ?>
                    <img src="<?php echo $image_path; ?>" alt="Image<?php //echo $user_name;  ?>">
                    <?php
                }
                else {
                    $str = '';

                    $name = ltrim(rtrim($user_name));

                    $name = str_replace('  ', ' ', $name);

                    $count = 0;

                    $array = str_split($name);

                    foreach ($array as $char) {

                        if ($count <= 1) {

                            $count++;

                            $str .= $char;
                        }
                    }
                    if ($count <= 1) {
                        $str .= '.';
                    }
                    $str = ltrim(rtrim(strtoupper($str)));
                    ?>
                    <div class="thumb_name"><?php echo $str; ?></div>
                    <?php
                }
                ?>
            </a>
            <div class="usy-name">
                <h3> 
                    <?php echo $user_name; ?>
                </h3>
                <span> 
                    <small>
                        <i class="fa fa-phone"></i>
                        <span class="phone_no"><?php echo $record->phone; ?></span>
                    </small> 
                </span>
                <br />
                <span>                
                    <small>
                        <i class="fa fa-clock"></i>
                        <span class="phone_no"><?php echo $created_at; ?></span>
                    </small>
                </span>
            </div>
        </div>
        <?php
    }

}