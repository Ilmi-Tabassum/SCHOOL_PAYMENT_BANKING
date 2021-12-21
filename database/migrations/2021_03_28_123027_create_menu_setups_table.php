<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_setups', function (Blueprint $table) {
            $table->id();
            $table->string("sub_id", 50)->nullable();
            $table->string("menu_name", 255)->unique()->nullable();
            $table->string("menu_title", 255)->nullable();
            $table->string("menu_url", 255)->unique()->nullable();
            $table->string("menu_icon", 50)->nullable();
            $table->integer("status")->default(1)->comment("0: Inactive, 1: Active, 2: Delete");
            $table->unsignedBigInteger("created_by")->nullable();
            $table->unsignedBigInteger("updated_by")->nullable();
            $table->unsignedBigInteger("deleted_by")->nullable();
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
        Schema::dropIfExists('menu_setups');
    }
}
