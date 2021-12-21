<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class  CreateTransactionListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_lists', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->string('student_id');
            $table->string('amount');
            $table->string('order_id')->nullable();
            $table->string('trx_id')->nullable();
            $table->string('bank_trx_id')->nullable();
            $table->string('return_code')->nullable();
            $table->string('status')->nullable();
            $table->string('method')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('trn_date');
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
        Schema::dropIfExists('transaction_lists');
    }
}
