<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeavesQuotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(191);
        Schema::create('leaves_quota', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_id');
            $table->string('branch_id');
            $table->string('employee_id');
            $table->string('leave_id');
            $table->integer('alloted_days');
            $table->float('used_days');
            $table->timestamps();
            $table->unique(array('company_id','branch_id','employee_id','leave_id'),'u');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leaves_quota');
    }
}
