<?php

namespace App\Lib;

use App\Enums\SubmissionPdfType;
use App\Lib\QR;
use App\Models\Sticker;
use App\Models\Submission;
use Barryvdh\DomPDF\Facade\Pdf; 
use Barryvdh\DomPDF\PDF as DomPDF;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubmissionPdf
{
    protected DomPDF $pdf;
    protected Submission $submission;
    protected Sticker $sticker;
    protected SubmissionPdfType $type;
    protected string $qrPath;
    protected string $signaturePath;

    public function __construct(Submission $submission, Sticker $sticker, SubmissionPdfType $type)
    {
        $this->submission = $submission;
        $this->sticker = $sticker;
        $this->type = $type;

        // Generate PDF
        Pdf::setOption([
            'defaultFont' => 'sans-serif',
            'dpi' => 150,
        ]);

        // Generate QR code
        $qr = QR::generateQrCode($this->sticker->url, 'png');
        $this->qrPath = Storage::disk('public')->path(env('APP_PATH_STICKERS') . '/' . $this->sticker->hash . '_qr.png');
        $qrPng = $qr->getString();
        file_put_contents($this->qrPath, $qrPng);

        // Get signature path
        $this->signaturePath = Storage::disk('public')->path('signatures/' . $submission->hash . '.png');

        $this->pdf = Pdf::loadView('pdf.submission', [
            'qrPath' => $this->qrPath,
            'signaturePath' => $this->signaturePath,
            'submission' => $this->submission,
            'type' => $this->type,
        ]);
    }

	public function download(): Response
    {
        $filename = $this->submission->filename;
        $filename = str_replace('/', '-', $filename);
        $filename = str_replace('.pdf', ' - ' . Str::of($this->type->value)->upper() . '.pdf', $filename);

        return $this->pdf->download($filename);
    }  

	public function save(string $path): string
    {
        $filename = $this->submission->filename;
        $filename = str_replace('.pdf', ' - ' . Str::of($this->type->value)->upper() . '.pdf', $filename);
		$path = rtrim($path, '/') . '/' . $filename;

        // Save PDF
        $this->pdf->save($path);    
		return $path;
    }

    public function view(): View
    {
        return view('pdf.submission', [
            'qrPath' => $this->qrPath,
            'signaturePath' => $this->signaturePath,
            'submission' => $this->submission,
            'type' => $this->type,
        ]);
    }
}