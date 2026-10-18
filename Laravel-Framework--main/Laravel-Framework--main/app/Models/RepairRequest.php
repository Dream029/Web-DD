<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'technician_id',
        'title',
        'description',
        'location',
        'priority',
        'status',
        'image_path',
        'resolution_note'
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
