<?php

 namespace App\Http\Controllers;
//  namespace App;

use Illuminate\Http\Request;
use App\Product;
use App\WishProduct;
use App\UserWishes;
use App\User;
use App\Spec;
use Auth;
use Intervention\Image\ImageManagerStatic as Image1;
class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function tg(Request $request)
    {
        // $encrypted = Crypt::encryptString('Hello world.');
        // return Crypt::decryptString($encrypted);
        // return bcrypt(env('KEY1', false).'4895142232120006');
        return Product::with('ctg')->get();
    }
    public function tokenRefresh(Request $request)
    {
        $token = Str::random(60);

        $request->user()->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        return ['token' => $token];
    }
    public function imgUpload(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:8096'
        ]);
        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }
        $ext = 'png';
        $img = Image1::make($request->file('image'));
        $img->resize(1024, 1024, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $img->encode($ext);
        $hash = md5($img->__toString());
        \Storage::put($hash.'.'.$ext, $img);
        return $hash.'.'.$ext;
    }
    public function productCreate(Request $data)
    {
        if($data->file('image')->isValid()){
            $ext = 'png';
            $img = Image1::make($data->file('image'));
            $img->resize(1024, 1024, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->encode($ext);
            $hash = md5($img->__toString());
            // $watermark = Storage::get('watermark.png');
            // $img->insert($watermark);
            //  return Storage::putFile('public',new File($img->__toString()));
            //    return  $img->response('png');
            \Storage::put($hash.'.'.$ext, $img);
            $item =  new Product;
            
            $item->name = $data->name;
            $item->category_id = 1;//$data->name,
            $item->discount_id = 0;//$data->discount_id;
            $item->img_src=$hash.'.'.$ext;
            $item->description=$data->desc;
            $item->rating = 0;
            $item->vote_count = 0;
            $item->available = $data->count;
            $item->is_bestseller = 0;
            $item->price = $data->price;
            $item->price_provider = 0;
            $item->is_visible = 0;
            $item->arrive_date = 0;
            $item->save();
            return $item;
        }else return '0';
    }
    public function initFilters(Request $request) {
        $categories = Product::get(['id','category_id'])->groupBy('category_id');
    
        foreach ($categories as $category_id => $category) {
            $product_id = [];
            $filters = [];
            foreach ($category as $product) {
               $product_id[] = $product->id;
            }
            $spec = \App\Spec::whereIn('prod_id',$product_id)->where('isFilterable','1')->get(['prod_id','name','value'])->groupBy('name');
            foreach ($spec as $name => $value) {
                $val = $value->groupBy('value');
                $vvs = [];
                foreach ($val as $value => $arr) {
                    $ids = [];
                    foreach ($arr as $key => $v1) {
                        array_push($ids,['product_id'=>$v1->prod_id]);
                    }
                    array_push($vvs,[['priority'=>2,'popularity'=>0,'value'=> $value],$ids]);
                }
                $temp  = \App\Filter::updateOrCreate(
                    ['category_id'=> $category_id,'name'=> $name,'desc'=> 'some text']
                );
                
                foreach ($vvs as $key => $v2) {
                    $filter_value = \App\FilterValue::with('prod_ids')->where('filter_id',$temp->id)->where('value',$v2[0]['value'])->first();
    
                    if($filter_value){
                        //** Disable for postgres ID primary key error*/
                        // if(count($filter_value->prod_ids)){
                        //     foreach ($v2[1] as $value) {
                        //         if(!$filter_value->prod_ids->contains('product_id', $value['product_id'])){
                        //             \App\FilterValueProduct::create([
                        //                 'filter_value_id' => $filter_value->id,
                        //                 'product_id' => $value['product_id']
                        //             ]);
                        //         }
                        //     }
                        // }
                        // else $filter_value->prod_ids()->createMany($v2[1]);
                    }
                    else{
                        \App\FilterValue::create((function($temp,$v3){
                            return ['filter_id'=>$temp->id,'priority'=>$v3['priority'],
                            'popularity'=> $v3['popularity'],'value'=>$v3['value']];
                        })($temp,$v2[0]));
                        // ->prod_ids()->createMany($v2[1]);
                        //** Disable for postgres */
                    }
                }
            }
        }
        return 'done';
    }
}
