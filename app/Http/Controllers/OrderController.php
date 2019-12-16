<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\PaymentService;
use App\Classes\VisaPayment;
use App\Classes\CashPayment;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

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
                    if ($request->payment != 'visa') {
                        return;
                    }
                    if (!(
                        preg_match('/^(\d{13,19})$/', $value['number']) &&
                        preg_match('/^(\d{2})$/', $value['expire']['year']) &&
                        preg_match('/^([0][1-9]|[1][0-2])$/', $value['expire']['month']) &&
                        preg_match('/^(\d{3,4})$/', $value['cvv2'])
                    )
                    ) {
                        $fail($attribute.' is invalid.');
                    }
                }
            ]
        ]);

        switch ($request->payment) {
            case 'visa':
                $this->paymentService->checkout(new VisaPayment);
                break;
            case 'cash':
                $this->paymentService->checkout(new CashPayment);
                break;
        }
    }
}
