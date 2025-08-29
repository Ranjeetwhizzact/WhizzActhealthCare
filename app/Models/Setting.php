<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'setting';

    protected $fillable = [
        'hospital_name',
        'poc_name',
        'poc_mobile',
        'poc_email',
        'hospital_email',
        'hospital_phone',
        'hospital_address',
        'logo',
        'letter_head',
        'domain',
        'reference_name',
        'is_active'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


}
