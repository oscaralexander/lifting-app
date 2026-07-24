<?php

namespace App\Lib;

use App\Models\Inspection;
use App\Services\DocRaptorService;
use App\Services\OutsmartService;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Enums\OutputFormat;
use Spatie\PdfToImage\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

abstract class InspectionPdf
{
    protected int $thumbnailSize = 800;

    public function __construct(protected readonly Inspection $inspection) {}

    abstract protected function view(): string;

    abstract protected function storagePath(): string;

    abstract protected function thumbnailPath(): string;

    public function html(): string
    {
        return view($this->view(), ['inspection' => $this->inspection])->render();
    }

    /**
     * Stream the stored PDF as a download. The document is only rendered when
     * nothing has been stored yet, so this is instant for existing documents.
     */
    public function download(): StreamedResponse
    {
        return $this->stream($this->storedPdf());
    }

    /**
     * Render a fresh PDF, replacing the stored copy and its thumbnail.
     */
    public function generate(): string
    {
        $bytes = app(DocRaptorService::class)->htmlToPdf($this->html());

        Storage::disk('local')->put($this->storagePath(), $bytes);

        $this->generateThumbnail($bytes);

        return $bytes;
    }

    /**
     * Attach the PDF to the work order this inspection was imported from.
     *
     * Returns null when the inspection is not linked to a work order.
     */
    public function sendToOutsmart(string $bytes): ?bool
    {
        if (! $this->inspection->outsmart_work_order_id) {
            return null;
        }

        return app(OutsmartService::class)->addWorkOrderDocument(
            $this->inspection->outsmart_work_order_id,
            $this->filename(),
            $bytes,
        );
    }

    public function stream(string $bytes): StreamedResponse
    {
        return response()->streamDownload(function () use ($bytes) {
            echo $bytes;
        }, $this->filename(), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function save(string $path): string
    {
        $fullPath = rtrim($path, '/').'/'.$this->filename();
        file_put_contents($fullPath, $this->storedPdf());

        return $fullPath;
    }

    public function clearCache(): void
    {
        Storage::disk('local')->deleteDirectory(dirname($this->storagePath()));
    }

    public function thumbnailUrl(): ?string
    {
        $path = $this->thumbnailPath();

        if (! Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    protected function storedPdf(): string
    {
        $storagePath = $this->storagePath();

        if (Storage::disk('local')->exists($storagePath)) {
            return Storage::disk('local')->get($storagePath);
        }

        return $this->generate();
    }

    protected function filename(): string
    {
        return basename($this->storagePath());
    }

    protected function generateThumbnail(string $bytes): void
    {
        $temporaryPdf = tempnam(sys_get_temp_dir(), 'inspection_pdf_');
        rename($temporaryPdf, $temporaryPdf .= '.pdf');
        file_put_contents($temporaryPdf, $bytes);

        $thumbnailPath = $this->thumbnailPath();
        Storage::disk('public')->makeDirectory(dirname($thumbnailPath));

        try {
            (new Pdf($temporaryPdf))
                ->selectPage(1)
                ->format(OutputFormat::Jpg)
                ->thumbnailSize($this->thumbnailSize)
                ->save(Storage::disk('public')->path($thumbnailPath));
        } catch (Throwable $e) {
            report($e);
        } finally {
            @unlink($temporaryPdf);
        }
    }
}
