<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    use \Awobaz\Compoships\Compoships;
    
    protected $table ='rosters';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id','branch_id','employee_id', 'department_id','shift_id',
        'date','is_holiday',
        'final_half_1','final_half_2',
        
        'updated_at','created_at'
    ];

    public function branch(){
        return $this->belongsTo('App\Branch', ['branch_id','company_id'],['branch_id','company_id']);
    }
    public function department(){
        return $this->belongsTo('App\Department',['department_id','company_id'],['department_id','company_id']);
    }
    public function shift(){
        return $this->belongsTo('App\Shift', ['shift_id','company_id'],['shift_id','company_id']);
    }
    public function employee(){
        return $this->belongsTo('App\Employee', ['employee_id','company_id'],['employee_id','company_id']);
    }

    public function punch_record(){
        return $this->belongsTo('App\Punch','id','roster_id');
    }
}
