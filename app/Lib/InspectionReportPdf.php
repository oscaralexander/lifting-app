<?php

namespace App\Lib;

use App\Models\Inspection;
use App\Services\DocRaptorService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class InspectionReportPdf
{
    protected string $logoBase64;

    public function __construct(protected readonly Inspection $inspection)
    {
        $this->logoBase64 = base64_encode(file_get_contents(public_path('assets/img/lifting-inspections-fc.svg')));
    }

    public function html(): string
    {
        return view('pdf.inspection-report', [
            'inspection' => $this->inspection,
        ])->render();
    }

    public function download(): Response
    {
        return response($this->pdf(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$this->filename().'"',
        ]);
    }

    public function save(string $path): string
    {
        $fullPath = rtrim($path, '/').'/'.$this->filename();
        file_put_contents($fullPath, $this->pdf());

        return $fullPath;
    }

    public function clearCache(): void
    {
        Storage::disk('local')->deleteDirectory('inspections/reports/'.$this->inspection->hash);
    }

    protected function pdf(): string
    {
        if (app()->environment('local')) {
            return $this->generate();
        }

        $storagePath = $this->storagePath();

        if (Storage::disk('local')->exists($storagePath)) {
            return Storage::disk('local')->get($storagePath);
        }

        $bytes = $this->generate();

        Storage::disk('local')->put($storagePath, $bytes);

        return $bytes;
    }

    protected function storagePath(): string
    {
        return 'inspections/reports/'.$this->inspection->hash.'/'.$this->filename();
    }

    protected function generate(): string
    {
        $html = view('pdf.inspection-report', [
            'inspection' => $this->inspection,
            'logoBase64' => $this->logoBase64,
        ])->render();

        return app(DocRaptorService::class)->htmlToPdf($html);
    }

    protected function filename(): string
    {
        $object = $this->inspection->inspectionObject;

        return implode('_', array_filter([
            $this->inspection->created_at->format('Ymd'),
            $this->inspection->hash,
            $object?->manufacturer ? strtolower(str_replace(' ', '-', $object->manufacturer)) : null,
            $object?->model ? strtolower(str_replace([' ', '/'], '-', $object->model)) : null,
        ])).'.pdf';
    }
}
