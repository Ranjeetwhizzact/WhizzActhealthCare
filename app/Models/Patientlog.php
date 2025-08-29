<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patientlog extends Model
{
    protected $fillable = [
        'name',
        'email',
        'message',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $table = 'patientlogs';
}
