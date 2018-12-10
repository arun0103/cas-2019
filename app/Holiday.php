<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $table ='holidays';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'holiday_id','name', 
        'company_id', 'branch_id',
        'holiday_date','holiday_description',
        'updated_at','created_at'
    ];
}
