<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Order\Order;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $statuses = [
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED,
                Order::STATUS_REFUNDED,
                Order::STATUS_ON_HOLD,
            ];

            $table->increments('id')->unsigned();
            $table->integer('userId')->unsigned()->nullable();
            $table->enum('status', $statuses)->default(Order::STATUS_PENDING);
            $table->enum('paymentMethod', ['paypal', 'cod'])->default('paypal');
            $table->string('currency', 10)->default('USD');
            $table->integer('subTotal')->default(0)->unsigned();

            $table->string('coupon', 100)->nullable();
            $table->integer('discount')->default(0)->unsigned();
            $table->string('taxRateCountry', 5)->nullable();
            $table->integer('taxRateAmount')->default(0)->unsigned();
            $table->integer('taxAmount')->default(0)->unsigned();

            $table->integer('total')->default(0)->unsigned();

            $table->string('refNo', 20);
            $table->boolean('allowDownload')->default(0)->unsigned();
            $table->dateTime('completedDate')->nullable();
            $table->dateTime('paidDate')->nullable();
            $table->string('ipAddress')->nullable();
            $table->text('userAgent')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['refNo'], 'uniqueRefNo');
            $table->index(['userId', 'coupon']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
