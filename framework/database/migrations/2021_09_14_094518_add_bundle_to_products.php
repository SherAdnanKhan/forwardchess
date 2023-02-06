<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBundleToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('discountPrice', 'discount');

            $table
                ->enum('discountType', ['percent', 'amount'])
                ->default('amount')
                ->after('price');

            $table->boolean('isBundle')->after('active');
        });
    }
}
