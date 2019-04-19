<?php

use Illuminate\Database\Seeder;

class FilterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filters = [];
        $category_products = App\Product::get(['id', 'category_id'])->groupBy('category_id');
        foreach ($category_products as $category_id => $products) {
            $products_ids = $products->pluck('id');
            $specs = App\Spec::whereIn('prod_id',$products_ids)->where('isFilterable','1')->get(['prod_id','name','value'])->groupBy('name');
            foreach ($specs as $name => $spec) {
                $spec_values = $spec->groupBy('value');
                $filter = \App\Filter::create(
                    ['category_id' => $category_id, 'name' => $name, 'desc' => 'some text']
                );
                $filter_values = [];
                // $spec->pluck('prod_id')
                foreach ($spec_values as $value => $spec) {
                    $filter_values[] = [
                        'filter_id' => $filter->id,
                        'priority' => 2,
                        'popularity'=> 0,
                        'value'=> $value
                    ];
                }
                $filter->values()->createMany($filter_values);
            }
        }
    }
}
