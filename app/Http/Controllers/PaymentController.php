<?php

namespace App\Http\Controllers;

use App\Resolvers\PaymentPlatformResolver;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    protected $paymentPlatformResolver;

    public function __construct(PaymentPlatformResolver $paymentPlatformResolver)
    {
        $this->middleware('auth');

        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }
    public function pay(Request $request)
    {
        //dd($request);
        /* $rules = [
            'payment_platform' => ['required', 'exists:payment_platforms,id'],
        ];

        $request->validate($rules); */

        $paymentPlatform = $this->paymentPlatformResolver
            ->resolveService(4);

        session()->put('paymentPlatformId', 4);

        return $paymentPlatform->handlePayment($request);
    }
}
