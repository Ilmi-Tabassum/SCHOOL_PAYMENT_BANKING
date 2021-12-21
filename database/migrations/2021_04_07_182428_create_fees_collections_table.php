<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeesCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fees_collections', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->string('year_id')->nullable();
            $table->unsignedBigInteger('fees_id')->nullable();
            
            $table->string('payable_amount')->nullable();
            $table->string('received_amount')->nullable();
            $table->string('payment_date')->nullable();
            $table->unsignedBigInteger('school_id')->nullable();
            
            $table->integer("status")->nullable();

            $table->integer("created_by")->nullable();
            $table->integer("updated_by")->nullable();
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
        Schema::dropIfExists('fees_collections');
    }
}
