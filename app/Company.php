<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use \Awobaz\Compoships\Compoships;
    
    protected $table ='companies';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id','company_type','name', 'website','contact',
         'country', 'state','city','street_address_1','street_address_2','postal_code',
         'lat','lng','VAT_number','PAN_number','registration_number','updated_at','created_at', 'added_by'
    ];

    public function branches(){
        return $this->hasMany('App\Branch','company_id','company_id');
    }
    public function departments(){
        return $this->hasManyThrough('App\Department','App\Branch');
    }
    public function employees(){
        return $this->hasMany('App\Employee','company_id','company_id');
    }
}
