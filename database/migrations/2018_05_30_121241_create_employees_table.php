<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
        	$table->increments('id');
            // personal info
            $table->string('employee_id');
            $table->string('name');
            $table->string('mobileNumber1', 15)->nullable();
            $table->string('mobileNumber2', 15)->nullable();
            $table->string('email')->nullable();

            $table->string('country', 50);
            $table->string('state', 50);
            $table->string('city', 50);
            $table->string('street_address_1', 50);
            $table->string('street_address_2', 50)->nullable();
            $table->unsignedInteger('postal_code');
            
            $table->date('dob');
            $table->boolean('gender');  // male = 1, female = 0
            $table->string('marital_status')->nullable();
            $table->date('anniversary')->nullable();
            $table->string('father_name');
            $table->string('educational_qualification')->nullable();
            $table->string('professional_qualification')->nullable();
            $table->float('experience')->nullable();

            $table->text('image')->nullable()->default(NULL);
            $table->text('imageType')->nullable()->default(NULL);

            // $table->text('scanned1')->nullable()->default(NULL);
            // $table->text('scanned1_Type')->nullable()->default(NULL);
            // $table->text('scanned2')->nullable()->default(NULL);
            // $table->text('scanned2_Type')->nullable()->default(NULL);
            // $table->text('scanned3')->nullable()->default(NULL);
            // $table->text('scanned3_Type')->nullable()->default(NULL);
            // $table->text('scanned4')->nullable()->default(NULL);
            // $table->text('scanned4_Type')->nullable()->default(NULL);
            // $table->text('scanned5')->nullable()->default(NULL);
            // $table->text('scanned5_Type')->nullable()->default(NULL);

            $table->integer('card_number');
            $table->string('dept_id');
            $table->string('category_id');
            $table->string('company_id');
            $table->string('branch_id');
            $table->string('designation_id');
            $table->boolean('Permanent_Temporary'); // permanent = 1, temporary = 0           
            
            // time office related
                        
            $table->integer('week_off_day')->nullable();
            $table->integer('additional_off_day')->nullable();
            $table->string('additional_off_week')->nullable(); // [1,2,3,4]
            $table->string('shift_1')->nullable();
            $table->string('shift_2')->nullable();
            $table->string('shift_3')->nullable();
            $table->string('shift_4')->nullable();
            $table->boolean('change_by_week')->nullable();
            $table->integer('change_after_days')->nullable();
            $table->integer('changed_on_day')->nullable();
            $table->string('half_day_shift')->nullable();  // yes = 1, no = 0
            $table->integer('half_day_on')->nullable();

            $table->boolean('comp_off_applicable');
            $table->boolean('overtime_applicable');

            $table->string('reporting_officer_1')->nullable();
            $table->string('reporting_officer_2')->nullable();

            $table->date('joining_date');
            $table->date('leaving_date')->nullable();

            

            $table->string('referred_by')->nullable();

            // Salary related information

            $table->string('ESI_number');
            $table->string('PF_number');
            $table->string('UAN_number');
            $table->string('PAN_number');
            $table->boolean('wage_type'); // daily = 0, monthly = 1

            $table->string('bank_name');
            $table->string('IFSC_code');
            $table->string('bank_branch');
            $table->string('bank_account_number');

            
            $table->timestamps();
            $table->unique(array('employee_id','company_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
