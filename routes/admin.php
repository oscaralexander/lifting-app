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
