<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveMaster extends Model
{
    use \Awobaz\Compoships\Compoships;
    
    protected $table ='leave';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'leave_id','name', 'company_id',
        'max_leave_allowed', 'min_leave_allowed',
        'weekly_off_cover','paid_holiday_cover',
        'club_with_leaves','cant_club_with_leaves',
        'balance_adjusted_from','treat_present','treat_absent',
        'updated_at','created_at'
    ];
}
