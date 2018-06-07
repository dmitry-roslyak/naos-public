<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\UserWishes;
use App\WishProduct;
use App\Discount;
use App\Product;
use App\Currency;
use App\UserMail;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\Mailer;
use App\tempMessage;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $value = file_get_contents('https://openexchangerates.org/api/latest.json?app_id=9d63c3fdce5f4b218824682ec539a810');
            $value = json_decode($value);
            $date = (new \DateTime)->format('Y-m-d');
            App\Currency::insert([
                'rate'=> $value->rates->USD,
                'name'=>'USD',
                'date'=> $date
            ]);
            App\Currency::insert([
                'rate'=> $value->rates->UAH,
                'name'=>'UAH',
                'date'=> $date
            ]);
            App\Currency::insert([
                'rate'=> $value->rates->RUB,
                'name'=>'RUB',
                'date'=> $date
            ]);
            $date = (new \DateTime)->modify('-1 day')->format('Y-m-d');
            $products = App\Product::get(['id','price']);
            $history = [];
            foreach ($products as $product) {
                array_push($history,['product_id' => $product->id,'price' => $product->price,'currency_type'=>'USD','sales'=>0, 'date'=>$date]);
            }
            App\Prices_history::insert($history);
        })->daily();

        // $schedule->call(function () {
        // })->hourly();
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
