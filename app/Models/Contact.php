<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['fullname','email','phone','city','message','created_by','updated_by'];

    private function userCan($permission)
    {
        return Auth::check() && Auth::user()->can($permission);
    }
}
