<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_id');
            $table->string('shift_id');
            $table->string('name');
            $table->string('start_time',9);
            $table->string('end_time',9);
            $table->integer('grace_late');
            $table->integer('grace_early');

            $table->timestamps();
            $table->unique(array('shift_id','company_id'));

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shifts');
    }
}
