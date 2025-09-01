<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'gender',
      'image',
      'signature',
      'education',
      'degree',
      'offline_fees',
      'online_fees',
  
        'availabel_days',
        'available_time',
        'year_of_experince',
        'associated_hospitals',
        'address',
        'status',
      
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $table = 'doctors';

}
