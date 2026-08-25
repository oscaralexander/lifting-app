<?php

use App\Http\Controllers\DownloadController;
use App\Http\Controllers\ImportBelgiumController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\InspectionAppendixHtmlController;
use App\Http\Controllers\InspectionCertificateHtmlController;
use App\Http\Controllers\InspectionReportHtmlController;
use App\Http\Controllers\SubmissionDownloadController;
use App\Livewire\App\QR\Link;
use App\Livewire\App\QR\Show;
use App\Livewire\App\Submission\Form;
use App\Livewire\App\Submission\FormSelect;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('admin'))->middleware('auth:web')->name('home');

Route::get('download', DownloadController::class)->name('download');
Route::get('import', ImportController::class)->name('import');
Route::get('import-be', ImportBelgiumController::class)->name('import-be');

Route::get('locale/{locale}', function (string $locale) {
    app()->setLocale($locale);

    return redirect()->back()->withCookie(cookie()->forever('locale', $locale));
})->where('locale', '[a-z]{2}')->name('locale');

Route::get('qr/{hash}', Show::class)->name('qr.show');
Route::get('qr/link/{hash}', Link::class)->where('hash', '[A-Za-z0-9]{5}')->name('qr.link');

Route::get('submission/download', SubmissionDownloadController::class)->name('submission.download');
Route::get('submission/{stickerHash}', FormSelect::class)->name('submission.form-select');
Route::get('submission/{stickerHash}/form/{formId}', Form::class)->name('submission.form');
Route::get('submission/{stickerHash}/{hash}', App\Livewire\App\Submission\Show::class)->name('submission.show');
Route::get('inspection/{hash}/html', InspectionReportHtmlController::class)->name('inspection.html');
Route::get('inspection/{hash}/certificate/html', InspectionCertificateHtmlController::class)->name('certificate.html');
Route::get('inspection/{hash}/appendix/html', InspectionAppendixHtmlController::class)->name('appendix.html');

Route::get('info', function () {
    exit(phpinfo());
});
