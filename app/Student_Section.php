<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student_Section extends Model
{
    use \Awobaz\Compoships\Compoships;
    
    protected $table ='student_sections';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'institution_id', 'grade_id', 'section_id','name',
        
        'updated_at','created_at'
    ];

    public function students(){
        return $this->hasMany('App\Student',['section_id','institution_id'],['section_id','institution_id']);
    }
    public function grade(){
        return $this->belongsTo('App\Student_Grade', ['grade_id','institution_id'],['grade_id','institution_id']);
    }
}
