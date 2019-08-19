<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student_Shift extends Model
{
    use \Awobaz\Compoships\Compoships;
    
    protected $table ='institutions_shifts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shift_id','name',  'institution_id',
        'start_time', 'end_time',
        'weekly_off',
        'updated_at','created_at'

    ];
    public function rosters(){
        //return $this->hasMany('App\Roster', ['shift_id','institution_id'],['shift_id','company_id']);
    }
}
