<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_shifts', function (Blueprint $table) {
            $table->increments('id');

            $table->string('shift_id');
            $table->string('name');
            $table->string('institution_id');
            $table->string('start_time',9);
            $table->string('end_time',9);
            $table->integer('grace_late');
            $table->integer('grace_early');

            $table->timestamps();
            $table->unique(array('shift_id','institution_id'),'usi');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students_shifts');
    }
}
