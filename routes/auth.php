<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['guest', 'layout:guest'])->group(function () {
    Route::get('activate/{token}', App\Livewire\Auth\Activate::class)->name('auth.activate');

    Route::livewire('sign-in', 'pages::auth.sign-in')->name('auth.sign-in');

    // Route::get('sign-in', App\Livewire\Auth\SignIn::class)->name('auth.sign-in');
    Route::get('forgot-password', App\Livewire\Auth\ForgotPassword::class)->name('auth.forgot-password');
    Route::get('reset-password', App\Livewire\Auth\ResetPassword::class)->name('auth.reset-password');
});

Route::middleware(['auth:web'])->group(function () {
    Route::post('logout', App\Http\Controllers\Auth\LogOutController::class)->name('auth.logout');
});
