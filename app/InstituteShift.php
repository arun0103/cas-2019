<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstituteShift extends Model
{
    protected $table ='institutions_shifts';
    
    protected $fillable = [
        'id','institution_id','shift_id','name',
        'start_time', 'end_time',
        'weekly_off',
        'updated_at','created_at'
    ];
}
