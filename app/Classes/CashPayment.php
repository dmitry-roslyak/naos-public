<?php
namespace App\Classes;

use Illuminate\Http\Request;
use App\Classes\IPayment;

class CashPayment implements IPayment
{
    public function pay(Request $request, $cash_amount, $order_id)
    {
        return [
            'order_state' => 'new'
        ];
    }
}
