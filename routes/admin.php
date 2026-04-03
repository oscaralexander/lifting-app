<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::dashboard')->name('admin');

Route::prefix('clients')
    ->group(function () {
        Route::livewire('/', 'pages::clients')->name('clients');
    });

Route::prefix('inspections')
    ->group(function () {
        Route::livewire('/', 'pages::inspections')->name('inspections');
        Route::livewire('/{formSlug}/{inspectionObjectId}/{inspectionHash?}', 'pages::inspections.form')->name('inspections.form');
    });

Route::prefix('inspection-objects')
    ->group(function () {
        Route::livewire('/', 'pages::inspection-objects')->name('inspection-objects');
        Route::livewire('/{inspectionObject}', 'pages::inspection-objects.show')->name('inspection-objects.show')->where('inspectionObject', '[0-9]+');
    });

Route::prefix('schemas')
    ->group(function () {
        Route::livewire('/', 'pages::schemas')->name('schemas');
        Route::livewire('/{formSlug}', 'pages::schemas.edit')->name('schemas.edit')->where('schema', '[a-z0-9-]+');
    });

Route::prefix('users')
    ->group(function () {
        Route::livewire('/', 'pages::users')->name('users');
        Route::livewire('/create', 'pages::users.create')->name('users.create');
        Route::livewire('/{user}/edit', 'pages::users.edit')->name('users.edit')->where('user', '[0-9]+');
    });

// Route::prefix('forms')
//     ->group(function () {
//         Route::get('/', App\Livewire\Admin\Forms\Index::class)->name('admin.forms');
//         Route::get('/{formId}/edit', App\Livewire\Admin\Forms\Edit::class)->name('admin.forms.edit');
//     });

// Settings
Route::get('settings', App\Livewire\Admin\Settings\Index::class)->name('admin.settings');

// Route::prefix('users')
//     ->group(function () {
//         Route::get('/', App\Livewire\Admin\Users\Index::class)->name('admin.users');
//         Route::get('/create', App\Livewire\Admin\Users\Create::class)->name('admin.users.create');
//         Route::get('/{user}/edit', App\Livewire\Admin\Users\Edit::class)->name('admin.users.edit');
//     });
