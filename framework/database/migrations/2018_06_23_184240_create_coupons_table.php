<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->enum('type', ['percent', 'amount'])->default('percent');
            $table->string('name', 250)->nullable();
            $table->string('code', 100)->unique();
            $table->integer('discount')->unsigned()->default(0);
            $table->date('startDate');
            $table->date('endDate');

            $table->integer('minAmount')->unsigned()->default(0);
            $table->integer('usageLimit')->unsigned()->default(0);
            $table->integer('usages')->unsigned()->default(0);
            $table->boolean('uniqueOnUser')->default(0);
            $table->boolean('excludeDiscounts')->default(0);
            $table->boolean('firstPurchase')->default(0);

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
        Schema::dropIfExists('promotions');
    }
}
