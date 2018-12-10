<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppliedLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applied_leaves', function (Blueprint $table) {
            $table->increments('id');

            $table->string('company_id');
            $table->string('emp_id');
            $table->string('leave_id');
            $table->float('applied_days');
            $table->float('posted_days')->nullable();

            $table->date('leave_from');///////////
            $table->date('leave_to');/////////////
            $table->tinyInteger('day_part'); // whole day = 3, first-half = 1 , second-half = 2

            $table->date('comp_off_date_1')->nullable();
            $table->date('comp_off_date_2')->nullable();

            $table->longText('remarks')->nullable();
            $table->boolean('status')->nullable(); // approved = 1, rejected = 0
            $table->integer('approved_by')->nullable(); // employee_id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applied_leaves');
    }
}
