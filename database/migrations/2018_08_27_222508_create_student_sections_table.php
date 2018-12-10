<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_sections', function (Blueprint $table) {
            $table->increments('id');

            $table->string('institution_id');
            $table->string('grade_id');
            $table->string('section_id');
            $table->string('name');

            $table->timestamps();
            $table->unique(array('institution_id','section_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_section');
    }
}
