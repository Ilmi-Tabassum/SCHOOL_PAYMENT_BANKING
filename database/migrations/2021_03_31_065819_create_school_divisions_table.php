<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolDivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_divisions', function (Blueprint $table) {
            $table->id();
            $table->string("division_name");
            $table->integer("status")->default(1);
        });

        DB::table('school_divisions')->insert(
                                    array(  
                                            ['division_name' => 'Dhaka', 'status' => 1],
                                            ['division_name' => 'Chittagong', 'status' => 1],
                                            ['division_name' => 'Khulna', 'status' => 1],
                                            ['division_name' => 'Rajshahi', 'status' => 1],
                                            ['division_name' => 'Rangpur', 'status' => 1],
                                            ['division_name' => 'Sylhet', 'status' => 1],
                                            ['division_name' => 'Barisal', 'status' => 1],
                                            ['division_name' => 'Mymensingh', 'status' => 1],
                                    )
                                ); 
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_divisions');
    }
}
