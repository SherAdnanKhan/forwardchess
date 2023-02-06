<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(OAuthClientTableSeeder::class);
         $this->call(TaxRatesTableSeeder::class);
         $this->call(UsersTableSeeder::class);
         $this->call(CategoriesTableSeeder::class);
         $this->call(ProductsTableSeeder::class);
         $this->call(OrdersTableSeeder::class);
         $this->call(FaqTableSeeder::class);
    }
}
