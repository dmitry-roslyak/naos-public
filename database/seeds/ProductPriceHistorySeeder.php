<?php

use Illuminate\Database\Seeder;

class ProductPriceHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $price_history = [];
        $products = App\Product::get(['id', 'price']);
        $dates = App\Currency::select('date')->distinct('date')->orderBy('date', 'desc')->pluck('date');

        if(!count($dates) || !count($products)) throw new Exception("Empty models: ".App\Product::class." and(or) ".App\Currency::class);

        foreach ($products as $key => $product) {
            foreach ($dates as $key => $date) {
                array_push($price_history,[
                    'product_id'=> $product->id,
                    'date' => $date,
                    'price' => $product->price * $faker->randomFloat(3, 0.5, 2),
                    'sales' => $faker->randomDigit,
                    'currency_type' => 'USD'
                ]);
            }
        }
        DB::table('prices_histories')->insert($price_history);
    }
}
