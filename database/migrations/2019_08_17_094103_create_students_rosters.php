<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsRosters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_rosters', function (Blueprint $table) {
            $table->increments('id');

            $table->string('institution_id');
            $table->string('student_id');
            $table->string('shift_id');
            $table->date('date');
            $table->string('is_holiday',1);
            $table->timestamp('punch_in')->nullable();
            $table->timestamp('punch_out')->nullable();
 
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
        Schema::dropIfExists('students_rosters');
    }
}
