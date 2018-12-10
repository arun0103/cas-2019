<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_id');
            $table->string('branch_id');
            $table->string('name');
            $table->string('country', 50);
            $table->string('state', 50);
            $table->string('city', 50);
            $table->string('street_address_1', 50);
            $table->string('street_address_2', 50);
            $table->unsignedInteger('postal_code');
            $table->string('website')->nullable();
            $table->string('contact');
            $table->string('VAT_number')->nullable();
            $table->string('PAN_number')->nullable();
            $table->string('registration_number')->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->timestamps();
            $table->unique(array('branch_id','company_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
}
