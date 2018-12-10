<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentPunchRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_punch_records', function (Blueprint $table) {
            $table->increments('id');

            $table->string('institution_id');
            $table->string('student_id');

            $table->date('punch_date');
            $table->timestamp('punch_1');  // punch time to be recorded
            $table->timestamp('punch_2')->nullable();
            $table->timestamp('punch_3')->nullable();
            $table->timestamp('punch_4')->nullable();
            $table->timestamp('punch_5')->nullable();
            $table->timestamp('punch_6')->nullable();

            $table->integer('early_in')->nullable();
            $table->integer('early_out')->nullable();
            $table->integer('late_in')->nullable();
            $table->integer('overstay')->nullable();

            $table->timestamps();
            $table->unique(array('institution_id','student_id','punch_date'),'unique_punches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_punch_records');
    }
}
