<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngredientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredient', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',30);
            $table->string('quantity',30)->default(' ');
            $table->string('measurement',30)->default(' ');
            $table->string('preparation')->default(' ');
            $table->string('get_from',30)->default(' ');
            $table->integer('recipe_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('ingredient',function (Blueprint $table){
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
        Schema::dropIfExists('ingredient');
    }
}
