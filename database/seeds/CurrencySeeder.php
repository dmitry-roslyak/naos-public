<?php

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datE_now = new \DateTime;
        $currency = []; 
        $value = '{
            "disclaimer": "Usage subject to terms: https://openexchangerates.org/terms",
            "license": "https://openexchangerates.org/license",
            "timestamp": 1555027145,
            "base": "USD",
            "rates": {
                "RUB": 64.6036,
                "UAH": 26.832292
            }
        }';
        try {
            $data = file_get_contents(__DIR__ .'./../backup/currency.json');
            $data = json_decode($data);
        } catch (\Throwable $th) {
            echo "Incorrect backup file, loading currency data from external api...\n";
        }

        if(!empty($data->rows)) {
            foreach ($data->rows as $key => $value) {
                $currency[] = collect($value)->all();
            }
        } else {
            array_push($currency, [
                'rate' => 1,
                'name' => 'USD',
                'date' => '2019-01-01'
            ]);
            for ($j=0; $j < 20; $j++) { 
                $date = $datE_now->format('Y-m-d');
                $value = file_get_contents("https://openexchangerates.org/api/historical/{$date}.json?app_id=9d63c3fdce5f4b218824682ec539a810&symbols=UAH%2CRUB&prettyprint=false");
                $value = json_decode($value);
                if(!empty($value->rates)) {
                    foreach ($value->rates as $key => $value) {
                        array_push($currency, [
                            'rate' => $value,
                            'name' => $key,
                            'date' => $date
                        ]);
                    }
                }
                $datE_now->modify('-1 day');
            }
        };
        DB::table('currencies')->insert($currency);
    }
}
