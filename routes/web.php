<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/cart', function () {
    return view('cart-page');
})->name('cart');

Route::get('/checkout', function () {
    return view('checkout-page');
})->name('checkout');

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

require __DIR__.'/settings.php';
