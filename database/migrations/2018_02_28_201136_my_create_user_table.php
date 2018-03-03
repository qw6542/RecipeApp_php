<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MyCreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
        public function up()
    {


        Schema::create('user', function (Blueprint $table) {
            $table->increments('id') ;
            $table->string('name',32);
            $table->string('username', 32);
            $table->string('email',32);
            $table->string('password', 64);
            $table->rememberToken()->nullable();
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
        Schema::dropIfExists('user');
    }
}
