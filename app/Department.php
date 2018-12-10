<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use \Awobaz\Compoships\Compoships;
    
    protected $table ='departments';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'department_id','name', 'company_id',
        'updated_at','created_at'
    ];
    public function employees(){
        return $this->hasMany('App\Employee',['dept_id','company_id'],['department_id','company_id']);
    }
    public function punch_records(){
        return $this->hasMany('App\Punch',['dept_id','company_id'],['department_id','company_id']);
    }
    public function rosters(){
        return $this->hasMany('App\Roster',['department_id','company_id'],['department_id','company_id']);
    }
    
}
