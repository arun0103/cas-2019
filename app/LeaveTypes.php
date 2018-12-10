<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveTypes extends Model
{
    use \Awobaz\Compoships\Compoships;
    
    protected $table ='company_leave';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'leave_id','branch_id','company_id', 
        
        'updated_at','created_at'
    ];
    public function leaveDetail(){
        return $this->belongsTo('App\LeaveMaster',['leave_id','company_id'],['leave_id','company_id']);
    }
}
