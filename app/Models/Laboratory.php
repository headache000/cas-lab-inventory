<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    protected $fillable = ['name'];

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }

    public function borrowRecords()
    {
        return $this->hasManyThrough(BorrowRecord::class, Equipment::class);
    }
}
