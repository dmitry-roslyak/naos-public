<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CurrencySeeder::class,
            LangsSeeder::class,
            UsersSeeder::class,
            CategoryProductSpecSeeder::class,
            ProductPriceHistorySeeder::class, // run only after CurrencySeeder::class, CategoryProductSpecSeeder::class
        ]);
        // return null;
        factory('App\Comment',500)->create();
        // factory(App\Prices_history::class, 50)->create()->each(function ($u) {
        //     $u->currency()->save(factory(App\Currency::class)->make(['name'=>'USD','rate'=>1]));
        // });
    }
}
