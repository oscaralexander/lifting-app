<?php

namespace App\Http\Controllers;

use App\Enums\ImportType;
use App\Jobs\ImportCSV;
use App\Models\Import;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function __invoke()
    {
        $exports = array_filter(Storage::files(env('APP_PATH_IMPORTS')), fn ($file) => ! Str::of(pathinfo($file, PATHINFO_BASENAME))->startsWith('.'));
        rsort($exports);

        // Get last export from filesystem
        $lastExport = array_shift($exports);

        // Get last import from database
        $lastImport = Import::orderBy('id', 'desc')->first();

        if ($lastExport) {
            if ($lastImport && $lastExport <= $lastImport->filename) {
                // Already processed
                return 0;
            }

            // Create import record
            $import = Import::create([
                'filename' => pathinfo($lastExport, PATHINFO_BASENAME),
                'type' => ImportType::FTP,
            ]);

            // Dispatch job to import CSV
            ImportCSV::dispatch($import);
        }

        return 0;
    }
}
