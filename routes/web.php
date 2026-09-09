<?php

use App\Http\Controllers\ServerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Customer\VpnController;
use App\Http\Controllers\Customer\TopupController;
use App\Http\Controllers\Customer\TransactionController;

use App\Http\Controllers\Admin\PaymentSettingController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;

use App\Http\Controllers\Admin\VpnAccountController;

use App\Models\PaymentSetting;


/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
});


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    $user = auth()->user();

    /*
     * ADMIN
     */
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    /*
     * CUSTOMER
     */
    $paymentSettings = PaymentSetting::where('status', true)
        ->orderBy('type')
        ->orderBy('name')
        ->get();

    return view(
        'customer.dashboard',
        compact('paymentSettings')
    );

})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| ADMIN - DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', function () {

    return view('admin.dashboard');

})->middleware(['auth', 'admin'])
  ->name('admin.dashboard');



        /*
|--------------------------------------------------------------------------
| ADMIN - CUSTOMERS
|--------------------------------------------------------------------------
*/

Route::get('/admin/customers', [AdminCustomerController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.customers.index');

Route::get('/admin/customers/create', [AdminCustomerController::class, 'create'])
    ->middleware(['auth', 'admin'])
    ->name('admin.customers.create');

Route::post('/admin/customers', [AdminCustomerController::class, 'store'])
    ->middleware(['auth', 'admin'])
    ->name('admin.customers.store');

Route::get('/admin/customers/{customer}', [AdminCustomerController::class, 'show'])
    ->middleware(['auth', 'admin'])
    ->name('admin.customers.show');

Route::get('/admin/customers/{customer}/edit', [AdminCustomerController::class, 'edit'])
    ->middleware(['auth', 'admin'])
    ->name('admin.customers.edit');

Route::put('/admin/customers/{customer}', [AdminCustomerController::class, 'update'])
    ->middleware(['auth', 'admin'])
    ->name('admin.customers.update');

Route::patch('/admin/customers/{customer}/toggle-status', [AdminCustomerController::class, 'toggleStatus'])
    ->middleware(['auth', 'admin'])
    ->name('admin.customers.toggle-status');

Route::patch('/admin/customers/{customer}/reset-password', [AdminCustomerController::class, 'resetPassword'])
    ->middleware(['auth', 'admin'])
    ->name('admin.customers.reset-password');

Route::delete('/admin/customers/{customer}', [AdminCustomerController::class, 'destroy'])
    ->middleware(['auth', 'admin'])
    ->name('admin.customers.destroy');

    /*
|--------------------------------------------------------------------------
| ADMIN - VPN ACCOUNTS
|--------------------------------------------------------------------------
*/

Route::get('/admin/vpn-accounts', [VpnAccountController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.vpn-accounts.index');


/*
|--------------------------------------------------------------------------
| ADMIN - SERVERS
|--------------------------------------------------------------------------
*/

Route::get('/admin/servers', [ServerController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.servers.index');

Route::get('/admin/servers/create', [ServerController::class, 'create'])
    ->middleware(['auth', 'admin'])
    ->name('admin.servers.create');

Route::post('/admin/servers', [ServerController::class, 'store'])
    ->middleware(['auth', 'admin'])
    ->name('admin.servers.store');

Route::get('/admin/servers/{id}/edit', [ServerController::class, 'edit'])
    ->middleware(['auth', 'admin'])
    ->name('admin.servers.edit');

Route::put('/admin/servers/{id}', [ServerController::class, 'update'])
    ->middleware(['auth', 'admin'])
    ->name('admin.servers.update');

Route::delete('/admin/servers/{id}', [ServerController::class, 'destroy'])
    ->middleware(['auth', 'admin'])
    ->name('admin.servers.destroy');

Route::get('/admin/servers/{id}/test', [ServerController::class, 'test'])
    ->middleware(['auth', 'admin'])
    ->name('admin.servers.test');
    Route::get('/admin/servers/{id}/monitor', [ServerController::class, 'monitor'])
    ->middleware(['auth', 'admin'])
    ->name('admin.servers.monitor');


/*
|--------------------------------------------------------------------------
| ADMIN - PAYMENT SETTINGS
|--------------------------------------------------------------------------
*/

Route::get('/admin/payment-settings', [PaymentSettingController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.payment-settings.index');

Route::post('/admin/payment-settings', [PaymentSettingController::class, 'store'])
    ->middleware(['auth', 'admin'])
    ->name('admin.payment-settings.store');

Route::put('/admin/payment-settings/{paymentSetting}', [PaymentSettingController::class, 'update'])
    ->middleware(['auth', 'admin'])
    ->name('admin.payment-settings.update');

Route::patch('/admin/payment-settings/{paymentSetting}/toggle', [PaymentSettingController::class, 'toggle'])
    ->middleware(['auth', 'admin'])
    ->name('admin.payment-settings.toggle');

Route::delete('/admin/payment-settings/{paymentSetting}', [PaymentSettingController::class, 'destroy'])
    ->middleware(['auth', 'admin'])
    ->name('admin.payment-settings.destroy');


/*
|--------------------------------------------------------------------------
| ADMIN - TRANSACTIONS
|--------------------------------------------------------------------------
*/

Route::get('/admin/transactions', [AdminTransactionController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.transactions.index');

Route::post('/admin/transactions/{transaction}/approve', [AdminTransactionController::class, 'approve'])
    ->middleware(['auth', 'admin'])
    ->name('admin.transactions.approve');

Route::post('/admin/transactions/{transaction}/reject', [AdminTransactionController::class, 'reject'])
    ->middleware(['auth', 'admin'])
    ->name('admin.transactions.reject');

Route::delete('/admin/transactions/{transaction}', [AdminTransactionController::class, 'destroy'])
    ->middleware(['auth', 'admin'])
    ->name('admin.transactions.destroy');


/*
|--------------------------------------------------------------------------
| CUSTOMER - DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])
    ->middleware(['auth'])
    ->name('customer.dashboard');


/*
|--------------------------------------------------------------------------
| CUSTOMER - VPN
|--------------------------------------------------------------------------
*/

Route::get('/customer/vpn', [VpnController::class, 'index'])
    ->middleware(['auth'])
    ->name('customer.vpn.index');

Route::post('/customer/vpn', [VpnController::class, 'store'])
    ->middleware(['auth'])
    ->name('customer.vpn.store');

Route::put('/customer/vpn/{vpnAccount}', [VpnController::class, 'update'])
    ->middleware(['auth'])
    ->name('customer.vpn.update');

Route::delete('/customer/vpn/{vpnAccount}', [VpnController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('customer.vpn.destroy');
    Route::post('/customer/vpn/{vpnAccount}/auto-renew', [VpnController::class, 'toggleAutoRenew'])
    ->middleware(['auth'])
    ->name('customer.vpn.auto-renew');
    Route::post('/customer/vpn/{vpnAccount}/resume', [VpnController::class, 'resumeSubscription'])
    ->middleware(['auth'])
    ->name('customer.vpn.resume');


/*
|--------------------------------------------------------------------------
| CUSTOMER - TRANSACTIONS
|--------------------------------------------------------------------------
*/

Route::get('/customer/transactions', [TransactionController::class, 'index'])
    ->middleware(['auth'])
    ->name('customer.transactions.index');


/*
|--------------------------------------------------------------------------
| CUSTOMER - TOP UP
|--------------------------------------------------------------------------
*/

Route::get('/customer/topup', [TopupController::class, 'index'])
    ->middleware(['auth'])
    ->name('customer.topup.index');

Route::post('/customer/topup', [TopupController::class, 'store'])
    ->middleware(['auth'])
    ->name('customer.topup.store');

/*
|--------------------------------------------------------------------------
| CUSTOMER - PENGATURAN AKUN
|--------------------------------------------------------------------------
*/

Route::get('/customer/settings', [ProfileController::class, 'customerSettings'])
    ->middleware(['auth'])
    ->name('customer.settings');

Route::patch('/customer/settings', [ProfileController::class, 'customerSettingsUpdate'])
    ->middleware(['auth'])
    ->name('customer.settings.update');

Route::patch('/customer/settings/password', [ProfileController::class, 'customerPasswordUpdate'])
    ->middleware(['auth'])
    ->name('customer.settings.password');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
