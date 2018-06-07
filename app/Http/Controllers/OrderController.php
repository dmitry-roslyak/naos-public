<?php

namespace App\Http\Controllers;
//  namespace App;

use Illuminate\Http\Request;
use App\Product;
use App\products_filter;
use App\Spec;
use App\Cart;
use Auth;
use App\Classes\VisaPayment;
use Ramsey\Uuid\Uuid;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function store(Request $data)
    {
        $products_count = count($data->products);
        $ids = [];
        for ($i=0; $i < $products_count; $i++) { 
            array_push($ids,$data->products[$i]['id']);
        }
        switch ($data->delivery) {
            case 'courier':
                //check deliver adr... e.t.c.
                break;
            case 'customer':
                //check deliver adr... e.t.c.
                break;
            default:
                return response(null,400);
                break;
        }
        $user_id = Auth::id();
        if(!$user_id){
            $user_id= 0;
            if($data->user_info['fname'] == null && $data->user_info['tel'] == null) 
                return response(null,400);
        }
        $query = Product::with('discount')->whereIn('id',$ids)->get();
        // return $query[0]->discount->discount;
        $total_price = 0;
        // $membership_discount=User::where('id',$user_id)->value('membership_discount');
        $membership_discount=0;
        $cart_uuid = Uuid::uuid4();
        $j=0; $length=count($query);
        if(!$length) return response(null,400);
        for(;$j<$length;$j++)
        {
            $count = 0;
            for ($i=0; $i < $products_count; $i++) { 
                if($data->products[$i]['id'] == $query[$j]->id){
                    $count = $data->products[$i]['count'];
                }
            }
            if($count<1) return response(null,400);

            $cart_item = new Cart;
            $cart_item->cart_uuid = $cart_uuid;
            $cart_item->prod_id = $query[$j]->id;
            $cart_item->prod_price = $query[$j]->price;
            $cart_item->prod_count = $count;
            $cart_item->discount = $query[$j]->discount_id;
            $cart_item->save();
            if($query[$j]->discount)
            {
                $discount=$query[$j]->price*$count/100*$query[$j]->discount->discount;
                if($membership_discount>0){
                    $total_price=$query[$j]->price*$count-$discount-
                    $query[$j]->price*$count/100*$membership_discount;
                }
                else $total_price=$query[$j]->price*$count-$discount;
            }
            else{
                if($membership_discount>0){
                    $total_price=$query[$j]->price*$count-
                    $query[$j]->price*$count/100*$membership_discount;
                }
                else $total_price=$query[$j]->price*$count;
            }
        }
        switch ($data->payment) {
            case 'pay_card':
                return VisaPayment::pay(
                    $data->delivery,$data->delivery_adr,
                    $user_id, 
                    $cart_uuid,
                    $total_price,$data->card,$data->user_info);
                break;
            case 'cash':
                return response(null,200);
                break;
            case 'mc_card':
                // MasterCardPayment::pay($total_price,$data->a,$data->b,$data->c);
                break;
            default:
                return response(null,400);
                break;
        }
    }
}
