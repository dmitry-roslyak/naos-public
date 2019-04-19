<?php

use Illuminate\Database\Seeder;

class LangsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = file_get_contents(__DIR__ .'./../backup/langs.json');
        $data = json_decode($data);
        $arr = [];
        foreach ($data->rows as $key => $value) {
            $arr[] = collect($value)->all();
        }
        DB::table('langs')->insert($arr);
    }
}
