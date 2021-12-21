<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_infos', function (Blueprint $table) {
            $table->id();
            $table->string("school_name");
            $table->integer("school_ein")->unique()->default(0);
            $table->string("school_mobile", 16)->nullable();
            $table->string("school_email")->nullable();
            $table->integer("school_dist")->default(0);
            $table->integer("school_div")->default(0);
            $table->integer("school_ps")->default(0);
            $table->string("school_address");
            $table->string("school_logo")->nullable();
            $table->unsignedBigInteger("created_by")->default(0);
            $table->unsignedBigInteger("updated_by")->default(0);
            $table->unsignedBigInteger("deleted_by")->default(0);
            $table->unsignedBigInteger("approved_by")->default(0);
            $table->unsignedBigInteger("confirm_by")->default(0);
            $table->datetime("approved_date")->nullable();
            $table->datetime("confirm_date")->nullable();
            $table->integer("status")->default(0)->comment("0=Pending, 1=Active, 2=Inactive, 3=Terminate, 4=Hold, 5=Approved ");
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
        Schema::dropIfExists('school_infos');
    }
}
