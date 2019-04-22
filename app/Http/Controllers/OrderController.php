<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Cart;
use App\Classes\Payment;
use Ramsey\Uuid\Uuid;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'products.*.id' => 'required|numeric|exists:products,id',
            'products.*.count' => 'required|integer|gte:1',
            'delivery' => 'required',
            'user_info.name' => 'required',
            'user_info.tel' => 'required',
            'payment' => Rule::requiredIf(function () use ($request) {
                return $request->payment == 'visa' || $request->payment == 'cash';
            }),
            'card' => [
                function ($attribute, $value, $fail) use ($request) {
                    if($request->payment != 'visa') return;
                    if (!(
                        preg_match('/^(\d{13,19})$/', $value['number']) &&
                        preg_match('/^(\d{2})$/', $value['expire']['year']) &&
                        preg_match('/^([0][1-9]|[1][0-2])$/', $value['expire']['month']) &&
                        preg_match('/^(\d{3,4})$/', $value['cvv2']))
                    ) $fail($attribute.' is invalid.');
                }
            ]
        ]);

        $total_price = 0;
        $products_count = count($request->products);
        $cart_uuid = Uuid::uuid4();
        $user_products = collect($request->products);
        $products = Product::with('discount')->whereIn('id', $user_products->pluck('id'))->get();

        foreach ($products as $key => $product) {
            $count = $user_products->firstWhere('id', $product->id)['count'];

            $cart_item = new Cart;
            $cart_item->cart_uuid = $cart_uuid;
            $cart_item->prod_id = $product->id;
            $cart_item->prod_price = $product->price;
            $cart_item->prod_count = $count;
            $cart_item->discount = $product->discount_id;
            $cart_item->save();
            $discount = 0;
            if(!empty($product->discount) && $product->discount->discount > 0) {
                $discount = $product->price * $count / 100 * $product->discount->discount;
            }
            $total_price += $product->price * $count - $discount;
        }

        $currency = \App\Currency::where('name', 'UAH')->orderBy('date','desc')->value('rate');
        if(empty($currency)) return response(null, 500);

        $cash_amount = round($total_price * $currency, 2);

		$order = new \App\Order;
        $order->user_id = \Auth::id(); 
        $order->cart_uuid = $cart_uuid;
		$order->deliver_type = $request->delivery;
		$order->deliver_adr = $request->delivery_adr;
		$order->price = $cash_amount;
		$order->payment_type = $request->payment;
		$order->name = $request->user_info['name'];
		$order->phone = $request->user_info['tel'];
        $order->save();

        $orderUpdate = [];
        switch ($request->payment) {
            case 'visa':
                $orderUpdate = Payment::VisaPay($request->card, $cash_amount, $order->id);
                if($orderUpdate['payment_state'] == 200) { 
                    $orderUpdate['order_state'] = 'new';
                } else $orderUpdate['order_state'] = 'payment fail';
                break;
            case 'cash':
                $orderUpdate['order_state'] = 'new';
                break;
        }
        $order->update($orderUpdate);
    }
}
