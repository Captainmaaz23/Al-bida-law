<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChooseUsDetail extends Model
{
    protected $fillable = ['choose_us_id', 'sub_heading', 'sub_summary', 'sub_image'];

    public function chooseUs()
    {
        return $this->belongsTo(ChooseUs::class, 'choose_us_id');
    }
}
