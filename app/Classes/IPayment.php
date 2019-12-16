<?php
namespace App\Classes;

use Illuminate\Http\Request;

interface IPayment
{
    public function pay(Request $request, $cash_amount, $order_id);
}
