<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAcademicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_academics', function (Blueprint $table) {
            $table->id();
            $table->integer("student_id")->comment("parent id");
            $table->integer("school_id");
            $table->integer("class_id");
            $table->integer("shift_id");
            $table->integer("section_id");
            $table->integer("session_id");
            $table->integer("group_id");
            $table->string("std_roll");
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
        Schema::dropIfExists('student_academics');
    }
}
