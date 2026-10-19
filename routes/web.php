<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/cart', function () {
    return view('cart-page');
})->name('cart');

Route::get('/checkout', function () {
    return view('checkout-page');
})->name('checkout');

Route::get('/order-success/{order}', function (Order $order) {
    return view('orders.success', compact('order'));
})->name('order.success');

Route::get('/orders/{order}/invoice', function (Order $order) {
    return view('orders.invoice', compact('order'));
})->name('orders.invoice');

Route::get('/admin/orders/{order}/print', function (Order $order) {
    return view('orders.invoice', compact('order'));
})->name('orders.print');

Route::get('/admin/orders/export-csv', function () {
    $orders = Order::with('items.product')->latest()->get();
    
    $headers = [
        'Content-Type' => 'text/csv; charset=utf-8',
        'Content-Disposition' => 'attachment; filename="orders_' . date('Y-m-d') . '.csv"',
    ];

    $callback = function () use ($orders) {
        $file = fopen('php://output', 'w');
        // UTF-8 BOM so Excel opens Arabic properly
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($file, [
            'رقم الطلب (ID)',
            'العميل (Client)',
            'الهاتف (Téléphone)',
            'المدينة (Ville)',
            'العنوان (Adresse)',
            'الحالة (Statut)',
            'سعر التوصيل (Livraison)',
            'الإجمالي (Total DT)',
            'التاريخ (Date)'
        ]);
        
        foreach ($orders as $o) {
            fputcsv($file, [
                $o->id,
                $o->name,
                $o->phone,
                $o->city,
                $o->address,
                $o->status,
                $o->delivery_fee,
                $o->total,
                $o->created_at->format('Y-m-d H:i'),
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
})->name('orders.export');

Route::middleware('guest')->group(function () {
    Route::get('/login', \App\Livewire\CustomerLogin::class)->name('login');
    Route::get('/register', \App\Livewire\CustomerRegister::class)->name('register');
});

Route::middleware(['auth'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'fr'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::get('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'fr'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
});

require __DIR__.'/settings.php';
