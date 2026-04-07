<?php

use App\Http\Controllers\Auth\LogOutController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest', 'layout:guest'])->group(function () {
    Route::livewire('activate/{token}', 'pages::auth.activate')->name('auth.activate');
    Route::livewire('sign-in', 'pages::auth.sign-in')->name('login');
    Route::livewire('forgot-password', 'pages::auth.forgot-password')->name('auth.forgot-password');
    Route::livewire('reset-password', 'pages::auth.reset-password')->name('auth.reset-password');
});

Route::middleware(['auth:web'])->group(function () {
    Route::post('logout', LogOutController::class)->name('auth.logout');
});
