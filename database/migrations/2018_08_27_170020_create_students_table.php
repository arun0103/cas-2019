<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');

            $table->string('institution_id');
            $table->string('student_id');
            $table->string('name',30);
            $table->integer('card_number');
            $table->string('grade_id');
            $table->string('section_id')->nullable();
            $table->date('dob');
            $table->string('permanent_address',50);
            $table->string('temporary_address',50)->nullable();
            $table->boolean('gender');  // male = 1, female = 0
            $table->string('father_name',30);
            $table->string('mother_name',30);
            $table->string('guardian_name',30);
            $table->string('guardian_relation',20);

            $table->string('email')->nullable();
            $table->string('contact_1_name',40)->nullable();
            $table->string('contact_2_name',40)->nullable();
            $table->string('contact_1_number',15)->nullable();
            $table->string('contact_2_number',15)->nullable();
            $table->string('sms_option',1)->nullable();

            $table->timestamps();
            $table->unique('card_number');
            $table->unique(array('institution_id','student_id','grade_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
