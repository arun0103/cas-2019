<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use \Awobaz\Compoships\Compoships;
    
    protected $table ='students';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'student_id','name','institution_id', 'grade_id', 'section_id','card_number','shift_id',
        'dob','gender',
        'permanent_address','temporary_address','email',
        'father_name','mother_name', 'guardian_name', 'guardian_relation',
        'contact_1_number','contact_2_number','contact_1_name','contact_2_name', 'sms_option',
        'updated_at','created_at'
    ];

    public function grade(){
        return $this->belongsTo('App\Student_Grade',['grade_id','institution_id'],['grade_id','institution_id']);
    }
    public function section(){
        return $this->belongsTo('App\Student_Section',['section_id','institution_id'],['section_id','institution_id']);
    }
    public function shift(){
        return $this->belongsTo('App\Student_Shift',['institution_id','shift_id'],['institution_id','shift_id']);
    }
    public function rosters(){
        return $this->hasMany('App\Student_Roster','student_id','student_id');
    }
    
}
