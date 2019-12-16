<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Classes\IPayment;

use App\Product;
use App\Cart;
use Ramsey\Uuid\Uuid;
use App\Order;
use Auth;

class PaymentService
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function checkout(IPayment $payment)
    {
        $total_price = 0;
        $products_count = count($this->request->products);
        $cart_uuid = Uuid::uuid4();
        $user_products = collect($this->request->products);
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
            if (!empty($product->discount) && $product->discount->discount > 0) {
                $discount = $product->price * $count / 100 * $product->discount->discount;
            }
            $total_price += $product->price * $count - $discount;
        }

        $currency = \App\Currency::where('name', 'UAH')->orderBy('date', 'desc')->value('rate');
        if (empty($currency)) {
            return response(null, 500);
        }

        $cash_amount = round($total_price * $currency, 2);

        $order = new \App\Order;
        $order->user_id = \Auth::id();
        $order->cart_uuid = $cart_uuid;
        $order->deliver_type = $this->request->delivery;
        $order->deliver_adr = $this->request->delivery_adr;
        $order->price = $cash_amount;
        $order->payment_type = $this->request->payment;
        $order->name = $this->request->user_info['name'];
        $order->phone = $this->request->user_info['tel'];
        $order->save();

        $order->update($payment->pay($this->request, $cash_amount, $order->id));
    }
}
