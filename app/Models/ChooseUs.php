<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChooseUs extends Model
{
    protected $fillable = [
        'heading',
        'summary',
        'image',
        'status',
    ];

    public function details()
    {
        return $this->hasMany(ChooseUsDetail::class, 'choose_us_id');
    }
}
