<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->integer('publisherId')->unsigned()->default(0);
            $table->string('title', 250);
            $table->text('description')->nullable();
            $table->string('author', 250)->nullable();
            $table->string('sku', 100)->unique();
            $table->string('url', 250);
            $table->string('image', 250)->nullable();
            $table->integer('price')->default(0);
            $table->integer('discountPrice')->default(0);
            $table->date('discountStartDate')->nullable();
            $table->date('discountEndDate')->nullable();
            $table->integer('nrPages')->unsigned()->default(0);
            $table->integer('totalSales')->unsigned()->default(0);
            $table->date('publishDate')->nullable();
            $table->integer('position')->unsigned()->default(0);
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->nullable();
            $table->boolean('active')->unsigned()->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
