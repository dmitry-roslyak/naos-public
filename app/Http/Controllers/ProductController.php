<?php

 namespace App\Http\Controllers;
//  namespace App;

use Illuminate\Http\Request;
use App\Product;
use App\Filter;
use App\Spec;
use Auth;
use App\UserWishes;
use App\Http\Traits\Utility;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function show_one(Request $data)
    {
        $user_id = Auth::id();
        $query = Product::with('specs', 'discount', 'user_rating');
        if($user_id)
        {
            $query->with(['wish' => function ($query) use ($user_id) {
                $query->where('user_id',$user_id);
            }]);
        }
        return $query->where('id', $data->id)->get()->first();
    }
    public function productsByIds(Request $data)
    {
        return Product::with('discount','specs')->whereIn('id', $data->ids)->get();
    }
    public function search(Request $data)
    {
        return Product::with('ctg','discount')->where('name', 'ilike', '%' . $data->name . '%')
            ->skip(0)->take(5)->orderBy('category_id','desc')->get();
    }
    public function history(Request $data) {
        $user = \Auth::user();
        $currency = ($user ? $user : Utility::locale())['currency'];
        
        return \App\Prices_history::with(['currency' => function ($query) use ($currency) {
            $query->where('name', $currency);
        }])->where('product_id',$data->id)->orderBy('date','desc')->skip(0)->take(20)->orderBy('date','asc')->get();
    }
    public function rnd(Request $data) {
        return Product::where('available','>',0)->inRandomOrder()->skip(0)->take(5)->get();
    }
    public function filterBy(Request $data)
    {
        if($data->skip>-1&&$data->take>0)
        {
            $query = Product::with('specs')->with('discount');

            if($data->ctg_id>0) $query->where('category_id',$data->ctg_id);

            if(!empty($data->f)){
                $filters = Filter::with(['values' => function ($query) use($data) {
                    $query->whereIn('id',$data->f)->increment('popularity');
                }])->whereHas('values', function ($q) use($data) {
                    $q->whereIn('id', $data->f);
                })->get();
               
                foreach ($filters as $filter) {
                    $name = $filter->name;
                    $values = $filter->values->pluck('value');
                    $query->whereHas('specs', function ($q) use($name, $values) {
                        $q->where('name', $name)->whereIn('value', $values);
                    });
                }
            }

            $user_id = Auth::id();
            if($user_id)
            {
                $query->with(['wish' => function ($query) use ($user_id) {
                    $query->where('user_id',$user_id);
                }]);
            }
            if($data->price[0]>0&&$data->price[1]>$data->price[0]){
                $query->whereBetween('price', [$data->price[0]-1, $data->price[1]+1]);
            }
            $temp = Product::where('category_id',$data->ctg_id)->get(['price']);

            switch ($data->ordby) {
                case 'asc_price':$val ='price';$order ='asc';break;
                case 'desc_price':$val ='price';$order ='desc';break;
                case 'byrating':$val ='rating';$order ='desc'; break;
                case 'bynewest':$val ='arrive_date';$order ='desc'; break;
                default: return [$query->count(), $query->skip($data->skip)->take($data->take)->get(), $temp];
            }
            return [$query->count(), $query->orderBy($val,$order)->skip($data->skip)->take($data->take)->get(), $temp];
        }
    }
}
