<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->integer('for_all')->nullable();
            $table->string('notification_title');
            $table->text('notification_body');
            $table->string('notification_attachment')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->integer("status")->default(1)->comment("0: Inactive, 1: Active, 2: Delete");
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
        Schema::dropIfExists('all_notifications');
    }
}
