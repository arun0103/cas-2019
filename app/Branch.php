<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use \Awobaz\Compoships\Compoships;
    
    protected $table ='branches';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id','name', 'website','contact','company_id',
         'country', 'state','city','street_address_1','street_address_2','postal_code',
         'lat','lng','VAT_number','PAN_number','registration_number','updated_at','created_at'
    ];

    public function company(){
        return $this->belongsTo('App\Company','company_id','company_id');
    }
    public function department(){
        return $this->hasMany('App\Department', ['branch_id','company_id'],['branch_id','company_id']);
    }
}
