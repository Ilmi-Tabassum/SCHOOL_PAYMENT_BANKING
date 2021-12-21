<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassWiseFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_wise_fees', function (Blueprint $table) {
            $table->id();
            $table->integer("fees_id");
            $table->bigInteger('school_id');
            $table->bigInteger('class_id');
            $table->bigInteger('year_id');
            $table->string('amount')->nullable();
            $table->integer('status')->default(1)->comment("0=inactive, 1=active");
            $table->integer("created_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->integer("payment_id")->nullable();
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
        Schema::dropIfExists('class_wise_fees');
    }
}
