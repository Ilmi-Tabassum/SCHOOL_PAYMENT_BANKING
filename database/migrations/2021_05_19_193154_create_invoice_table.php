<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('student_id');
            $table->double('total_amount');
            $table->string('payment_id')->comment('foreign key');
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->integer('status')->default(0);
            $table->integer('school_id')->nullable();
            $table->integer('class_id')->nullable();
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
        Schema::dropIfExists('invoice');
    }
}
