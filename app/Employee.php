<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use \Awobaz\Compoships\Compoships;
    
    protected $table ='employees';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id','name', 'email','mobileNumber1','mobileNumber2','contact',
         'country', 'state','city','street_address_1','street_address_2','postal_code',
         'dob','gender','marital_status','anniversary','father_name',
         'educational_qualification','professional_qualification','experience',
         'image','imageType',
         'card_number','dept_id','category_id','company_id','branch_id','designation_id','Permanent_Temporary',
         'week_off_day','additional_off_day','additional_off_week',
         'shift_1','shift_2','shift_3','shift_4','change_by_week','change_after_days','changed_on_day',
         'half_day_shift','half_day_on',
         'comp_off_applicable','overtime_applicable',
         'reporting_officer_1','reporting_officer_2',
         'joining_date','leaving_date','referred_by',
         'ESI_number','PF_number','UAN_number','PAN_number','wage_type',
         'bank_name','IFSC_code','bank_branch','bank_account_number','created_at','updated_at'
    ];
     
    public function company(){
        return $this->belongsTo('App\Company','company_id','company_id');
    }
    public function branch(){
        return $this->belongsTo('App\Branch','branch_id','branch_id');
    }
    public function department(){
        return $this->belongsTo('App\Department', 'dept_id','department_id');
    }
    public function designation(){
        return $this->belongsTo('App\Designation','designation_id','designation_id');
    }
    public function category(){
        return $this->belongsTo('App\Category', 'category_id','category_id');
    }

    public function punch_records(){
        return $this->hasMany('App\Punch','emp_id','employee_id');
    }
    public function rosters(){
        return $this->hasMany('App\Roster','employee_id','employee_id');
    }
    public function first_shift(){
        return $this->hasOne('App\Shift','shift_id','shift_1');
    }
    public function second_shift(){
        return $this->hasOne('App\Shift','shift_id','shift_2');
    }
    public function third_shift(){
        return $this->hasOne('App\Shift','shift_id','shift_3');
    }
    public function fourth_shift(){
        return $this->hasOne('App\Shift','shift_id','shift_4');
    }

    public function appliedLeaves(){
        return $this->hasMany('App\AppliedLeave','emp_id','employee_id');
    }
    public function leaveQuotas(){
        return $this->hasMany('App\LeaveQuota','employee_id','employee_id');
    }
}
