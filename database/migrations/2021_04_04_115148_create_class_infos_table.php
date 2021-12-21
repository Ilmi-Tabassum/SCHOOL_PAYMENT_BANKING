<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_infos', function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique();
            $table->integer("status")->default(1)->comment("0 : Inactive, 1: Active, 2: Delete");
            $table->integer("created_by")->default(0);
            $table->integer("updated_by")->default(0);
            $table->integer("deleted_by")->default(0);
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
        Schema::dropIfExists('class_infos');
    }
}
