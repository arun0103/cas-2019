<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_id')->unique();
            $table->string('company_type');
            $table->string('name');
            $table->string('country', 50);
            $table->string('state', 50);
            $table->string('city', 50);
            $table->string('street_address_1', 50);
            $table->string('street_address_2', 50);
            $table->unsignedInteger('postal_code');
            $table->string('website');
            $table->string('contact');
            $table->string('VAT_number')->nullable();
            $table->string('PAN_number')->nullable();
            $table->string('registration_number')->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->integer('added_by');
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
        Schema::dropIfExists('companies');
    }
}
