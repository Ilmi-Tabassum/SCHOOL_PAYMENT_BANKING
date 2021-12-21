<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_setups', function (Blueprint $table) {
            $table->id();
            $table->string('payment_user_name');
            $table->string('payment_url');
            $table->string('payment_password');
            $table->string('payment_unique_code');
            $table->string('payment_return_url');
            $table->string('school');
            $table->string('payment_webhook');
            $table->string('status');
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
        Schema::dropIfExists('payment_setups');
    }
}
