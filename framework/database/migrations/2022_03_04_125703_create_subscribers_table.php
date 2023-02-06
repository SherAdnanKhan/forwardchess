<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->integer('plan_id')->unsigned();
            $table->enum('payment_method', ['paypal', 'stripe'])->default('paypal');
            $table->integer('validity')->nullable();
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_to')->nullable();
            $table->string('item_number')->nullable();
            $table->string('txn_id')->nullable();
            $table->float('payment_gross')->nullable();
            $table->string('currency_code')->nullable();
            $table->string('subscr_id')->nullable();
            $table->string('payer_email')->nullable();
            $table->string('payment_status')->nullable();
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
        Schema::dropIfExists('subscribers');
    }
}
