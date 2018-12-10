<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_id')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('role')->nullable();
            $table->string('password');
            $table->integer('added_by')->nullable();
            $table->boolean('password_changed')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
