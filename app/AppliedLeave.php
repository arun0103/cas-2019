<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppliedLeave extends Model
{
    use \Awobaz\Compoships\Compoships;
    
    protected $table ='applied_leaves';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id','emp_id','leave_id', 'applied_days','posted_days','leave_from',
        'leave_to', 'day_part','comp_off_date_1','comp_off_date_2','remarks','status',
        'approved_by',
         
        'updated_at','created_at'
    ];
    public function employee(){
        return $this->belongsTo('App\Employee',['emp_id','company_id'],['employee_id','company_id']);
    }
    public function leave(){
        return $this->belongsTo('App\LeaveMaster','leave_id','leave_id');
    }
}
