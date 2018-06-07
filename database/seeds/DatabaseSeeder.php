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
        $specs_name=['freq','freq_boost','cores','mem_size','mem_iwidth','mem_freq','pwr_consum','tech_node'];
        $specs =
        [ 'smartphones' => 
            [
                'display_size' => ['4','4.5','4.7','5','5.2','5.5','5.8'],
                'display_resolution' => ['1280x720','1920x1080','1136x640','2960x1440'],
                'display_type' => ['IPS','AMOLED','Super AMOLED','LTPS','TFT'],
                "CPU_name" => ['Qualcomm Snapdragon 617','Samsung Exynos 7580','Qualcomm Snapdragon 410','Qualcomm Snapdragon 625','Apple A7'],
                'CPU_cores' => ['8','8','4','8','2'],
                'CPU_freq' => ['1.5','1.5','1.2','2','1.3'],
                'frontal_camera'=>['4','5','8','10','15','18','21'],
                'backend_camera' => ['4','5','8','10','15','18','21'],
                'ram' => ['1','2','3','4'],
                'mem' => ['16','32','64','128']
            ],
         'laptops' => 
            [
                'display_size' => ['11.6','15.6','13.3'],
                'display_resolution' => ['1366x768','1920x1080','1440x900'],
                'display_type' => ['WXGA','WXGA+','IPS'],
                "CPU_name" => ['Intel Atom Z3735F','Intel Celeron N3060','Intel Pentium N3710','AMD Quad-Core A6-7310','Core i5','Intel Core i5-7200U'],
                'CPU_cores' => ['4','2'],
                'CPU_freq' => ['1.83','2.48','1.60','2.7','3.1','2.0'],
                'ram' => ['2','4','8'],
                'ram_type' => ['DDR3','DDR3','DDR3L','LPDDR3','DDR4'],
                'ram_freq' => ['1333','1600','2133'],
                'mem' => ['32','500','128','256'],
                'GPU' => ['Intel HD Graphics','Intel HD Graphics 400','Intel HD Graphics 405','AMD Radeon R5 M430','Intel HD Graphics 6000','nVidia GeForce GT 940MX','AMD Radeon R4']
            ],
         'tablets' => 
            [
                'display_size' => ['10.1','8','9.6','7'],
                'display_resolution' => ['1280x800','2048x1536','1024х600','1920x1080'],
                'display_type' => ['IPS','AMOLED','Super AMOLED','LTPS','TFT'],
                "CPU_name" => ['MediaTek MT8321','Qualcomm Snapdragon 650','Qualcomm Snapdragon 425','T-Shark2','MediaTek MT8735А'],
                'CPU_cores' => ['8','6','4','2'],
                'CPU_freq' => ['1.5','1.8','1.2','2','1.3','1.45'],
                'ram' => ['1','2','3'],
                'mem' => ['16','32','64','128']
            ]
        ];
        $vports=['HDMI','DisplayPort','VGA','DVI'];
        $specs_pdimensions=['L','W','H'];
        $specs_val_type=[ 
            "CPU_freq"=>'GHz',
            "ram_freq"=>'MHz',
            "display_size"=>'"',
            "frontal_camera"=>'Mp',
            "backend_camera"=>'Mp',
            'ram' => 'GB',
            'mem' => 'GB',
            ];
        $specs_isComparable = [
            'CPU_freq' => '',
            'ram_freq' => '',
            'frontal_camera' => '',
            'backend_camera' => '',
            'ram' => '',
            'mem' => '',
            'CPU_cores' => '',
            'display_size' => ''
        ];
        $categories=['smartphones','laptops','tablets'];
        $faker = Faker\Factory::create();
        $datE_now = new \DateTime;
        
        for($k=0;$k<count($categories);$k++)
        {
            $prod_prev_id = App\Product::select('id')->latest('id')->first();
            if(isset($prod_prev_id->id)) $prod_prev_id = $prod_prev_id->id;
            else $prod_prev_id = 1;
            $category = new App\Category;
            $category->name = $categories[$k];
            $category->save();
            $data = []; $datE = [];
           
            for($j=0;$j<50;$j++)
            {
                $datE_now = $datE_now->modify('-1 day');
                $temp_date = $datE_now->format('Y-m-d');
                array_push($datE,$temp_date);
                array_push($data,[
                    'rate' => 1,
                    'name' => 'USD',
                    'date' => $temp_date
                ]);
                array_push($data,[
                    'rate' =>$faker->randomFloat(1,1,30),
                    'name' => 'UAH',
                    'date' => $temp_date
                ]);
                array_push($data,[
                    'rate' =>$faker->randomFloat(1,1,30),
                    'name' => 'RUB',
                    'date' => $temp_date
                ]);
            }
            DB::table('currencies')->insert($data);
            $products = [];
            for($i=0,$img_i=0;$i<55;$i++,$img_i++)
            {
                switch ($categories[$k]) {
                    case 'smartphones':
                        if($img_i>5) $img_i=0;
                        break;
                    case 'tablets':
                        if($img_i>3) $img_i=0;
                        break;
                    case 'laptops':
                        if($img_i>7) $img_i=0;
                        break;
                }
                array_push($products,[
                    'name' => $faker->text(32),
                    'category_id'=> $category->id,
                    'discount_id'=> $faker->numberBetween(0,1),
                    'img_src'=> $categories[$k].$img_i.'.png',
                    'description'=>$faker->text(700),
                    'rating' => $faker->randomFloat(2,0,5),
                    'vote_count' => $faker->randomDigit,
                    'available' => $faker->randomDigit,
                    'is_bestseller' => $faker->boolean,
                    'price' => $faker->randomFloat(1,1,1799),
                    'is_visible' => $faker->boolean,
                    'arrive_date' => $faker->dateTimeBetween($startDate = '-72 days', $endDate = '12 days')
                ]);   
            }
            DB::table('products')->insert($products);
            $prod_recent_id = App\Product::select('id')->latest('id')->first()->id;
            $price_history = [];
            $prod_specs = [];
            if($prod_prev_id == 1) $prod_prev_id = 0;
            for ($i=$prod_prev_id+1; $i <= $prod_recent_id; $i++) { 
            //    foreach ($specs as $ctg => $spec) {
                    foreach ($specs[$categories[$k]] as $key => $value) {
                        $values_count = count($value);
                        array_push($prod_specs,[
                            'prod_id'=> $i,
                            'name' => $key,
                            'value' => $value[mt_rand(0,$values_count-1)],
                            'val_type' => isset($specs_val_type[$key])?$specs_val_type[$key]:'',
                            'isComparable' => isset($specs_isComparable[$key])?1:0,
                            'isFilterable' => 1,
                            'category' => 'basic'
                        ]);      
                    }
                // }
                for ($j=0; $j < 31; $j++) { 
                    array_push($price_history,[
                        'product_id'=> $i,
                        'date' => $datE[$j],
                        'price' =>$faker->randomFloat(1,1,1799),
                        'sales' => $faker->randomDigit,
                        'currency_type' => 'USD'
                    ]);
                }
            }
            DB::table('specs')->insert($prod_specs);
            DB::table('prices_histories')->insert($price_history);
        }
        $test =new \DateTime();
        $test->format("Y-m-d H:i:s");
        DB::table('discounts')->insert([
                'promo_code' => '$faker->$sha1',
                'discount' => mt_rand(1,15),
                'begin_at' => $test,
                'end_at' => date_modify($test, '+1 day')
        ]);
        factory('App\User',10)->create();
        factory('App\Comment',500)->create();
        // factory(App\Prices_history::class, 50)->create()->each(function ($u) {
        //     $u->currency()->save(factory(App\Currency::class)->make(['name'=>'USD','rate'=>1]));
        // });
        //     $categories = Collection::times(3, function ($number) {
//     return factory(Category::class)->create(['name' => 'Category #'.$number]);
// });
    }
}
