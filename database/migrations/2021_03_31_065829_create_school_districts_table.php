<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_districts', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->integer("division_id");
            $table->integer("status")->default(1);
        });

         DB::table('school_districts')->insert(
            array(  
                    ['name' => 'Gazipur', 'division_id' => '1', 'status' => 1],
                    ['name' => 'Narayangang', 'division_id' => '1', 'status' => 1],
                    ['name' => 'Joypurhat', 'division_id' => '4', 'status' => 1],
                    ['name' => 'Bogura', 'division_id' => '4', 'status' => 1],
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
        Schema::dropIfExists('school_districts');
    }
}
