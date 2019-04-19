<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'fname' => $faker->name,
        'lname' => $faker->name,
        'currency' => 'USD',
        'language' => 'en',
        'tel' => $faker->numberBetween(100000000,900000000),
        'adr' => $faker->name,
        'pan' => Crypt::encryptString('4895142232120006'),
        'bstate' => 1,
        'password' => bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'category_id'=> 1,
        'discount_id'=> $faker->numberBetween(0,1),
        'img_src'=>'404.png',//$faker->imageUrl(640, 480,'technics'),
        'description'=>$faker->text,
        'rating' => $faker->randomFloat(2,0,5),
        'vote_count' => $faker->randomDigit,
        'available' => $faker->randomDigit,
        'is_bestseller' => $faker->boolean,
        'price' => $faker->randomFloat(1,100,900),
        'is_visible' => $faker->boolean,
        'arrive_date' => $faker->dateTimeBetween($startDate = '-72 days', $endDate = '12 days')
    ];
});
$factory->define(App\Spec::class, function (Faker\Generator $faker) {
    return [
        'prod_id'=> App\Product::select('id')->latest('id')->first()->id,
        'name' => $faker->name,
        'value' => $faker->randomDigit,
        'val_type' => 'unit',
        'isComparable' => 1,
        'isFilterable' => 1,
        'category' => 'basic'
    ];
});

$factory->define(App\Comment::class, function (Faker\Generator $faker) {
    return [
        'product_id'=> App\Product::all()->random()->id,
        'user_id' => App\User::where('bstate', '!=', 255)->get()->random()->id,
        'reply_id' =>0,
        'rating' => $faker->numberBetween(0,5),
        'message' => $faker->text,
        'like' => $faker->randomDigit,
        'dislike' => $faker->randomDigit
    ];
});
$factory->define(App\Prices_history::class, function (Faker\Generator $faker) {
    return [
        'product_id'=> App\Product::all()->random()->id,
        'date' => $faker->dateTimeBetween($startDate = '-12 month', $endDate = 'now'),
        'price' =>$faker->randomFloat(1,1,1799),
        'sales' => $faker->randomDigit,
        'currency_type' => 'USD'
    ];
});
$factory->define(App\Currency::class, function (Faker\Generator $faker) {
    return [
        'rate' =>$faker->randomFloat(1,1,30),
        'name' => 'USD',
        'date'=> App\Prices_history::all()->random()->date
    ];
});
$factory->define(App\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => 'test',
    ];
});
$factory->define(App\Discount::class, function (Faker\Generator $faker) {
    $date = new \DateTime();
    return [
        'promo_code' => '$faker->$sha1',
        'discount' => mt_rand(1,15),
        'begin_at' => $date,
        'end_at' => date_modify($date, '+28 day')
    ];
});
