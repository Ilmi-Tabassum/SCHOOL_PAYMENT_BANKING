<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagefinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managefines', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->string('school_id')->nullable();
            $table->string('year');
            $table->string('month');
            $table->double('amount', 10, 2);
            $table->integer('head_id')->nullable();
            $table->text('reasons')->nullable();
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
        Schema::dropIfExists('managefines');
    }
}
