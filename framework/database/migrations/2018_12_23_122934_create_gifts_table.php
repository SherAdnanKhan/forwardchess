<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gifts', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->integer('userId')->unsigned()->default(0);
            $table->string('code', 100)->unique();
            $table->integer('amount')->unsigned()->default(0);
            $table->integer('originalAmount')->unsigned()->default(0);
            $table->string('friendEmail');
            $table->string('friendName');
            $table->text('friendMessage');
            $table->integer('orderId')->unsigned()->default(0);
            $table->boolean('enabled')->default(0);
            $table->timestamp('expireDate')->nullable();
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
        Schema::dropIfExists('gifts');
    }
}
