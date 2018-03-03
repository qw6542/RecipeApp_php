<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MyCreateRecipeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema :: create('recipe',function (Blueprint $table){
            $table -> increments('id') -> unique();
            $table->integer('clicks') ->default(0);
            $table -> string('title');
            $table -> integer('rating') ->default(0);
            $table -> String('image');
            $table -> integer('user_id')->unsigned();;
            $table -> timestamps();
        });
/*
*caution foreign key must be added after user created
*/
        Schema :: table ('recipe', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipe');
    }
}
