<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use \Awobaz\Compoships\Compoships;
    
    protected $table ='categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id','name', 'company_id',
        'max_late_allowed','max_early_allowed',
        'max_short_leave_allowed', 'min_working_days_weekly_off',
        'weekly_off_cover','paid_holiday_cover',
        'updated_at','created_at'
    ];
}
