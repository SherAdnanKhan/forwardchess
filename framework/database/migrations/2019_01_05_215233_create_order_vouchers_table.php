<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_vouchers', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->integer('orderId')->unsigned();
            $table->integer('giftId')->unsigned()->nullable();
            $table->integer('amount')->unsigned()->default(0);
            $table->boolean('refunded')->unsigned()->default(0);
            $table->timestamps();

            $table->index('orderId');
            $table->index('giftId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_vouchers');
    }
}
