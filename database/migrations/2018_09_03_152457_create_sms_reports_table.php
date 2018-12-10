<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_reports', function (Blueprint $table) {
            $table->increments('id');

            $table->string('requestId');
            $table->integer('userId');
            $table->string('number');
            $table->string('desc');
            $table->integer('status');
            $table->timestamp('date');
            $table->string('senderId');

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
        Schema::dropIfExists('sms_reports');
    }
}
