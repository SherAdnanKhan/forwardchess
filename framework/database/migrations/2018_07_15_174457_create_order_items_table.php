<?php

use App\Models\Order\Item;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->integer('orderId')->unsigned();
            $table->enum('type', [Item::TYPE_PRODUCT, Item::TYPE_GIFT])->default(Item::TYPE_PRODUCT);
            $table->integer('productId')->unsigned()->nullable();
            $table->string('name');
            $table->integer('quantity')->unsigned()->default(0);
            $table->integer('unitPrice')->unsigned()->default(0);
            $table->integer('total')->unsigned()->default(0);

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
        Schema::dropIfExists('order_items');
    }
}
