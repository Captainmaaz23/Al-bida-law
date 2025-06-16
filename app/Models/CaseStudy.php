<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseStudy extends Model
{
    protected $fillable = ['title','image','attorny','client','started_date','end_date','decision','created_by','updated_by','status'];
}
