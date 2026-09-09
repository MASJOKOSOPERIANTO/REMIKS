<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VpnAccount;

class VpnAccountController extends Controller
{
    /**
     * Menampilkan seluruh akun VPN.
     */
    public function index()
    {
        $vpnAccounts = VpnAccount::with(['user', 'server'])
            ->latest()
            ->get();

        return view('admin.vpn-accounts.index', compact('vpnAccounts'));
    }
}
