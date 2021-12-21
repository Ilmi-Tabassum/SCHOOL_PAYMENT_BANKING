<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string("student_id")->unique()->comment("4_digit_of_school_code 4_digit_of_year 5_digit_of_autoincrement(0000 0000 00000), Ex - 1001202100001");
            $table->string("name_bn")->nullable();
            $table->string("name");
            $table->string("photo")->nullable();
            $table->integer("present_division_id");
            $table->integer("permanent_division_id");
            $table->integer("present_district_id");
            $table->integer("permanent_district_id");
            $table->integer("present_post_id");
            $table->integer("permanent_post_id");
            $table->string("present_address")->nullable();
            $table->string("permanent_address")->nullable();
            $table->string("date_of_birth")->nullable();
            $table->string("mobile_number");
            $table->string("email_address")->nullable();
            $table->string("blood_group")->nullable();
            $table->string("gender", 20);
            $table->datetime("admission_date")->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer("status")->default(1)->comment("0=inactive, 1=active, 2=delete");
            $table->integer("created_by")->default(0);
            $table->integer("updated_by")->default(0);
            $table->integer("deleted_by")->default(0);
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
        Schema::dropIfExists('students');
    }
}
