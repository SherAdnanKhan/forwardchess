<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_bundles', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('bundleId')->unsigned();
            $table->integer('productId')->unsigned();

            $table->foreign('bundleId')->references('id')->on('products');
            $table->foreign('productId')->references('id')->on('products');

            $table->timestamps();

            $table->unique(['bundleId', 'productId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bundles');
    }
}
