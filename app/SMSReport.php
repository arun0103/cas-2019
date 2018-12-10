<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SMSReport extends Model
{
    protected $table ='sms_reports';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'requestId','userId', 'number',
        'desc', 'status','date','senderId',
        'updated_at','created_at'
    ];
}
