<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPositionToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('position')->after('name')->default(0);
            $table->string('url', 250)->after('position')->index('searchByCategory');
        });

        $categories = \App\Models\Product\Category::all();
        foreach ($categories as $category) {
            $url           = preg_replace("/[^A-Za-z0-9 ]/", '', $category->name);
            $category->url = kebab_case($url);
            $category->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['position']);
            $table->dropColumn(['url']);
        });
    }
}
