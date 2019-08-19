<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student_Roster extends Model
{
    use \Awobaz\Compoships\Compoships;
    
    protected $table ='students_rosters';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'institution_id','student_id','shift_id',
        'date','is_holiday',
        'punch_in','punch_out',
        
        'updated_at','created_at'
    ];

    public function student(){
        return $this->belongsTo('App\Student', ['student_id','institution_id'],['student_id','institution_id']);
    }
    public function shift(){
        return $this->belongsTo('App\Shift', ['shift_id','institution_id'],['shift_id','institution_id']);
    }
    

    public function punch_record(){
        return $this->belongsTo('App\Student_Punch','id','roster_id');
    }
}
