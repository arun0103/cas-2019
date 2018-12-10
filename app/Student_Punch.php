<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student_Punch extends Model
{
    use \Awobaz\Compoships\Compoships;
    
    protected $table ='student_punch_records';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id','institution_id',
        'punch_date','punch_1','punch_2','punch_3','punch_4','punch_5','punch_6',
        'early_in','early_out','late_in','overstay',

        'updated_at','created_at'
    ];

    public function student(){
        return $this->belongsTo('App\Student', ['student_id','institution_id'], ['student_id','institution_id']);
    }
}
