<?php

namespace App\Lib;

use App\Models\Inspection;
use App\Services\DocRaptorService;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Enums\OutputFormat;
use Spatie\PdfToImage\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InspectionReportPdf
{
    protected string $logoBase64;

    public function __construct(protected readonly Inspection $inspection) {}

    public function html(): string
    {
        return view('pdf.inspection-report', ['inspection' => $this->inspection])->render();
    }

    public function download(): StreamedResponse
    {
        $bytes = $this->pdf();

        return response()->streamDownload(function () use ($bytes) {
            echo $bytes;
        }, basename($this->inspection->inspectionReportPath()), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function save(string $path): string
    {
        $fullPath = rtrim($path, '/').'/'.basename($this->inspection->inspectionReportPath());
        file_put_contents($fullPath, $this->pdf());

        return $fullPath;
    }

    public function clearCache(): void
    {
        Storage::disk('local')->deleteDirectory('inspections/reports/'.$this->inspection->hash);
    }

    public function thumbnailUrl(): ?string
    {
        $path = $this->inspection->inspectionReportThumbPath();

        if (! Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    protected function pdf(): string
    {
        if (app()->environment('local')) {
            $bytes = $this->generate();

            $this->generateThumbnail($bytes);

            return $bytes;
        }

        $storagePath = $this->inspection->inspectionReportPath();

        // if (Storage::disk('local')->exists($storagePath)) {
        //     return Storage::disk('local')->get($storagePath);
        // }

        $bytes = $this->generate();

        Storage::disk('local')->put($storagePath, $bytes);

        $this->generateThumbnail($bytes);

        return $bytes;
    }

    protected function generateThumbnail(string $bytes): void
    {
        $temporaryPdf = tempnam(sys_get_temp_dir(), 'inspection_report_');
        rename($temporaryPdf, $temporaryPdf .= '.pdf');
        file_put_contents($temporaryPdf, $bytes);

        $thumbnailPath = $this->inspection->inspectionReportThumbPath();
        Storage::disk('public')->makeDirectory(dirname($thumbnailPath));

        try {
            (new Pdf($temporaryPdf))
                ->selectPage(1)
                ->format(OutputFormat::Jpg)
                ->thumbnailSize(800)
                ->save(Storage::disk('public')->path($thumbnailPath));
        } catch (\Throwable $e) {
            report($e);
        } finally {
            @unlink($temporaryPdf);
        }
    }

    protected function generate(): string
    {
        $html = view('pdf.inspection-report', ['inspection' => $this->inspection])->render();

        return app(DocRaptorService::class)->htmlToPdf($html);
    }
}
