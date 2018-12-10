<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassRoutinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::defaultStringLength(191);
        Schema::create('class_routines', function (Blueprint $table) {
            $table->increments('id');

            $table->string('institution_id',30);
            $table->string('shift_id',30);
            $table->string('timing_id',30);
            $table->string('grade_id',30);
            $table->string('section_id',20);
            $table->string('subject_name');
            $table->string('teacher_id');

            $table->timestamps();
            $table->unique(array('institution_id','shift_id','timing_id','grade_id','section_id'),'u');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_routines');
    }
}
