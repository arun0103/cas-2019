<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRawDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_data', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('machine_id');
            $table->integer('card_number');
            $table->timestamp('punch_time');
            $table->boolean('status'); //true = processed ,  false = not processed
            $table->string('in_out',1)->nullable;  // in = I , out = O

            $table->timestamps();
            $table->unique(array('card_number','punch_time'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raw_data');
    }
}
