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
            FilterSeeder::class,
            ProductPriceHistorySeeder::class, // run only after CurrencySeeder::class, CategoryProductSpecSeeder::class
        ]);
        factory('App\Comment',500)->create();
    }
}
