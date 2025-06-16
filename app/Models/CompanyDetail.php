<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
    protected $fillable = ['email','phonenumber','facebook','snapchat','instagram','twitter','youtube','address','created_by','updated_by'];

    private function userCan($permission)
    {
        return Auth::check() && Auth::user()->can($permission);
    }

}
