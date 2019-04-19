<?php

use Illuminate\Database\Seeder;

class CategoryProductSpecSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
        $specs_pdimensions=['L','W','H'];
        $categories=['smartphones','laptops','tablets'];
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
        $i = 0; 
        factory(App\Category::class, 3)->make()->each(function($u) use (&$i, &$categories, &$specs, &$specs_val_type, &$specs_isComparable) {
            $category = $categories[$i++];
            $u->name = $category;
            $u->save();
            $category_id = $u->id;
            $img_i = 0;
            factory(App\Product::class, 55)->make()->each(function($u) use (&$img_i, &$category_id, &$category, &$specs, &$specs_val_type, &$specs_isComparable) {
                $u->category_id = $category_id;
                $u->img_src = $category.$img_i.'.png';
                $u->save();
                $product_id = $u->id;
                $img_i++;
                switch ($category) {
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
                $s = [];
                foreach ($specs[$category] as $key => $value) {
                    $values_count = count($value) - 1;
                    array_push($s,[
                        'prod_id'=> $product_id,
                        'name' => $key,
                        'value' => $value[mt_rand(0, $values_count)],
                        'val_type' => isset($specs_val_type[$key])?$specs_val_type[$key]:'',
                        'isComparable' => isset($specs_isComparable[$key])?1:0,
                        'isFilterable' => 1,
                        'category' => 'basic'
                    ]);      
                }
                DB::table('specs')->insert($s);
            });
        });
    }
}
