<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Slidder extends Model
{
    protected $fillable = ['image','text','status','created_by','updated_by'];

    private function userCan($permission)
    {
        return Auth::check() && Auth::user()->can($permission);
    }

}
