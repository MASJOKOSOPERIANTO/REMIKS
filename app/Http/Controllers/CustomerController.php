<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $paymentSettings = PaymentSetting::where('status', true)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('customer.dashboard', compact('paymentSettings'));
    }
}
