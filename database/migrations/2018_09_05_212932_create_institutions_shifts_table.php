<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitutionsShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institutions_shifts', function (Blueprint $table) {
            $table->increments('id');

            $table->string('institution_id');
            $table->string('shift_id');
            $table->string('name');
            $table->string('start_time',9);
            $table->string('end_time',9);
            $table->string('weekly_off');

            $table->timestamps();
            $table->unique(array('institution_id','shift_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('institutions_shifts');
    }
}
