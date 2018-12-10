<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RawData extends Model
{
    protected $table ='raw_data';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'card_number','punch_time','machine_id', 
        'status','in_out',
        
        'updated_at','created_at'
    ];
}
