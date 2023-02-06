<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// CREATE DATABASE forward_chess CHARACTER SET utf8 COLLATE utf8_general_ci;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('email', 100)->unique();
            $table->string('password')->nullable();
            $table->string('mobile')->nullable();
            $table->boolean('active')->default(1)->unsigned();
            $table->boolean('isAdmin')->default(0)->unsigned();

            $table->timestamp('lastLogin');
            $table->timestamp('email_verified_at')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
