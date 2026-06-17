<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DocRaptorService
{
    public function htmlToPdf(string $html, array $options = []): string
    {
        $response = Http::withBasicAuth(config('services.docraptor.key'), '')
            ->asJson()
            ->post(config('services.docraptor.url'), array_merge([
                'document_content' => $html,
                'type' => 'pdf',
                'test' => true, // ! app()->environment('production'),
            ], $options));

        return $response->body();
    }
}
