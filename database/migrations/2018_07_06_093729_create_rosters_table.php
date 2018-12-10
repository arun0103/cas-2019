<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rosters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee_id');
            $table->string('company_id');
            $table->string('branch_id')->nullable();
            $table->string('department_id')->nullable();
            $table->string('shift_id');
            $table->date('date');
            $table->string('is_holiday',1);
            $table->string('final_half_1',2)->nullable();
            $table->string('final_half_2',2)->nullable();
            $table->timestamps();

            $table->unique(array('employee_id','company_id','branch_id','date'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roasters');
    }
}
