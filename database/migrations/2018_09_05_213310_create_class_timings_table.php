<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_timings', function (Blueprint $table) {
            $table->increments('id');

            $table->string('institution_id');
            $table->string('shift_id');
            $table->string('timing_id');
            $table->string('description');
            $table->string('start_time',9);
            $table->string('end_time',9);

            $table->timestamps();
            $table->unique(array('institution_id','shift_id','timing_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_timings');
    }
}
