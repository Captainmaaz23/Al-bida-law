<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
    protected $fillable = ['name','description','image','status','arabic_title','arabic_description','created_by','updated_by'];

    private function userCan($permission)
    {
        return Auth::check() && Auth::user()->can($permission);
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }
}
