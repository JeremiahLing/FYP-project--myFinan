<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = ['staff_id', 'name', 'email', 'phone', 'ic_no', 'salary', 'position', 'user_id',];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
