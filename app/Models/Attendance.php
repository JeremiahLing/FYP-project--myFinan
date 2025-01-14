<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'staff_id', 'status'];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
