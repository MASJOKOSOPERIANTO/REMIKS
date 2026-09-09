<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Menampilkan riwayat transaksi customer.
     */
    public function index()
    {
        $transactions = Auth::user()
            ->transactions()
            ->latest()
            ->get();

        $paymentSettings = PaymentSetting::where('status', true)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('customer.transactions.index', compact(
            'transactions',
            'paymentSettings'
        ));
    }
}
