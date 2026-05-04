<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRecord extends Model
{
    protected $fillable = [
        'equipment_id',
        'borrower_name',
        'borrower_id_number',
        'borrower_role',
        'borrowed_at',
        'expected_return_at',
        'returned_at',
        'status',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'expected_return_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
