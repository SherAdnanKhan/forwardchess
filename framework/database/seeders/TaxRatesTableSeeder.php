<?php

use App\Models\TaxRate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxRatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tax_rates')->truncate();

        $results = DB::table('__old_fc_woocommerce_tax_rates')->get();

        foreach ($results as $oneResult) {
            TaxRate::create([
                'country' => $oneResult->tax_rate_country,
                'name'    => $oneResult->tax_rate_name,
                'rate'    => $oneResult->tax_rate,
            ]);
        }
    }
}
