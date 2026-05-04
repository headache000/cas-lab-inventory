<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = [
        'laboratory_id',
        'item_name',
        'category',
        'description',
        'model',
        'amount',
        'fund',
        'par_number',
        'property_number',
        'status',
        'acquired_date'
    ];

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }
}
