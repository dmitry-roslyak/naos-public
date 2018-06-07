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
class DashBoardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function tg(Request $data)
    {
         return App\Product::with('ctg')->get();
    }
    public function imgUpload(Request $data)
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
}
