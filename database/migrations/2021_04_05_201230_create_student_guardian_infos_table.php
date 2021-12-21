<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentGuardianInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_guardian_infos', function (Blueprint $table) {
            $table->id();
            $table->integer("student_id")->comment("student id");
            $table->string("father_name");
            $table->string("mother_name");
            $table->string("guardian_name")->nullable();
            $table->string("guardian_contact_no")->nullable();
            $table->string("father_nid")->nullable();
            $table->string("mother_nid")->nullable();
            $table->string("relation_with_student")->nullable();
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
        Schema::dropIfExists('student_guardian_infos');
    }
}
