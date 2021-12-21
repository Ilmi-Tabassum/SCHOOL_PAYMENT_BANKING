<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('status');
            $table->timestamps();
        });


         DB::table('user_types')->insert(
            array(  
                ['name' => 'Super Admin', 'status' => 1],
                ['name' => 'School Admin', 'status' => 1],
                ['name' => 'Guardian Access', 'status' => 1],
                ['name' => 'Bank Teller', 'status' => 1],
                ['name' => 'Bank Agent', 'status' => 1],
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
        Schema::dropIfExists('user_types');
    }
}
