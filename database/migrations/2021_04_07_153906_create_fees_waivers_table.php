<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeesWaiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fees_waivers', function (Blueprint $table) {
            $table->id();
            $table->string('year_id');
            $table->string('class_id');
            $table->string('student_id');
            $table->string('fees_amount')->nullable();
            $table->string('paid_waiver_amount')->nullable();
            $table->string('discount_amount')->nullable();
            $table->bigInteger('fees_id');
            $table->integer('status')->default(1)->comment('1=active,0=deactive');
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
        Schema::dropIfExists('fees_waivers');
    }
}
