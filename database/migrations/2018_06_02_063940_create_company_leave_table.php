<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_leave', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_id');
            $table->string('branch_id');
            $table->string('leave_id');
            $table->timestamps();

            $table->unique(array('leave_id','branch_id','company_id'));

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_leave');
    }
}
