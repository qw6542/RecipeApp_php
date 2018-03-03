<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('comment', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('comment');
            $table->integer('recipe_id')->unsigned();
            $table->integer('user_id')-> unsigned();
            $table->timestamps();
        });

        Schema::table('comment',function (Blueprint $table){
            $table ->foreign('recipe_id')->references('id')->on('recipe') ;
            $table ->foreign('user_id')->references('id')->on('user') ;

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment');
    }
}
