<?php

namespace App\Http\Controllers;

use App\Lib\InspectionReportPdf;
use App\Models\Inspection;

class InspectionReportPdfController extends Controller
{
    public function __invoke(string $hash)
    {
        if (! auth('web')->check()) {
            return redirect()->route('login');
        }

        $inspection = Inspection::with(['client', 'inspectionObject', 'inspectable'])
            ->where('hash', $hash)
            ->firstOrFail();

        return (new InspectionReportPdf($inspection))->download();
    }
}
