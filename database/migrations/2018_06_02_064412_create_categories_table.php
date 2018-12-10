<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_id');
            $table->string('category_id');
            $table->string('name');
            $table->integer('max_late_allowed');  // in minutes
            $table->integer('max_early_allowed');
            $table->integer('max_short_leave_allowed');
            $table->integer('min_working_days_weekly_off');

            $table->boolean('weekly_off_cover');
            $table->boolean('paid_holiday_cover');

            $table->timestamps();
            $table->unique(array('company_id','category_id'));

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
