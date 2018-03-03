<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDescriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('description', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('description');
            $table->integer('recipe_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('description',function (Blueprint $table){
            $table ->foreign('recipe_id')->references('id')->on('recipe') ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('description');
    }
}
