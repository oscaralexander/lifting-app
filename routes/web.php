<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\App\Index::class)->name('home');

Route::get('download', App\Http\Controllers\DownloadController::class)->name('download');
Route::get('import', App\Http\Controllers\ImportController::class)->name('import');
Route::get('import-be', App\Http\Controllers\ImportBelgiumController::class)->name('import-be');

Route::get('locale/{locale}', function (string $locale) {
    app()->setLocale($locale);
    return redirect()->back()->withCookie(cookie()->forever('locale', $locale));
})->where('locale', '[a-z]{2}')->name('locale');

Route::get('qr/{hash}', \App\Livewire\App\QR\Show::class)->name('qr.show');
Route::get('qr/link/{hash}', \App\Livewire\App\QR\Link::class)->where('hash', '[A-Za-z0-9]{5}')->name('qr.link');

Route::get('submission/download', App\Http\Controllers\SubmissionDownloadController::class)->name('submission.download');
Route::get('submission/{stickerHash}', \App\Livewire\App\Submission\FormSelect::class)->name('submission.form-select');
Route::get('submission/{stickerHash}/form/{formId}', \App\Livewire\App\Submission\Form::class)->name('submission.form');
Route::get('submission/{stickerHash}/{hash}', \App\Livewire\App\Submission\Show::class)->name('submission.show');
Route::get('submission/{stickerHash}/{hash}/download/{type}', App\Http\Controllers\SubmissionPdfController::class)->name('submission.pdf');

Route::get('info', function () {
    die(phpinfo());
});
