<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePunchRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('punch_records', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('emp_id');
            $table->string('company_id');
            $table->string('branch_id');
            $table->string('dept_id');
            $table->string('category_id'); 

            $table->integer('roster_id');
            $table->date('punch_date');
            $table->timestamp('punch_1');  // punch time to be recorded
            $table->timestamp('punch_2')->nullable();
            $table->timestamp('punch_3')->nullable();
            $table->timestamp('punch_4')->nullable();
            $table->timestamp('punch_5')->nullable();
            $table->timestamp('punch_6')->nullable();

            // $table->boolean('day_status1'); // next day = 1, same day = 0
            // $table->boolean('day_status2');
            // $table->boolean('day_status3');
            // $table->boolean('day_status4');
            // $table->boolean('day_status5');
            // $table->boolean('day_status6');

            $table->integer('early_in')->nullable();
            $table->integer('early_out')->nullable();
            $table->integer('late_in')->nullable();
            $table->integer('overstay')->nullable();
            $table->integer('overtime')->nullable();
            $table->float('comp_off')->nullable(); // in case of overstay comp of will be calculated 1 or .5
            $table->float('comp_off_avail')->nullable();
            $table->string('shift_code')->nullable();
            $table->integer('hour_worked_minutes')->nullable(); // store in minute
            $table->boolean('half_1')->nullable(); // yes = 1, no = 0 or blank
            $table->boolean('half_2')->nullable(); // yes = 1, no = 0 or blank
            $table->boolean('half_1_gate_pass')->nullable(); 
            $table->boolean('half_2_gate_pass')->nullable(); 
            $table->string('half_1_gp_out')->nullable();
            $table->string('half_1_gp_in')->nullable();
            $table->string('half_1_gp_hrs')->nullable();
            $table->string('half_2_gp_out')->nullable();
            $table->string('half_2_gp_in')->nullable();
            $table->string('half_2_gp_hrs')->nullable();

            $table->string('final_half_1')->nullable();
            $table->string('final_half_2')->nullable();

            $table->longText('remarks')->nullable();
            $table->boolean('is_manual_entry_done')->nullable(); // yes =1 , no = 0 or blank

            $table->integer('deduction_minutes')->nullable();/////////////
            $table->string('status',1)->nullable();// checking holiday status
            
            $table->timestamps();
            $table->unique(array('company_id','emp_id','punch_date'));

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('punch_records');
    }
}
