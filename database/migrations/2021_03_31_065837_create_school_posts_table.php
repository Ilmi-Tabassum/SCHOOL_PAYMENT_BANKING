<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_posts', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->integer("dist_id");
            $table->string("status")->default(1);

            $table->timestamps();
        });


        DB::table('school_posts')->insert(
            array(  
                    ['name' => 'Post1', 'dist_id' => 1, 'status' => 1],
                    ['name' => 'Post2', 'dist_id' => 1, 'status' => 1],
                    ['name' => 'Post3', 'dist_id' => 2, 'status' => 1],
                    ['name' => 'Post4', 'dist_id' => 2, 'status' => 1]
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
        Schema::dropIfExists('school_posts');
    }
}
