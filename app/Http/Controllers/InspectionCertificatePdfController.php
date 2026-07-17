<?php

namespace App\Http\Controllers;

use App\Lib\InspectionCertificatePdf;
use App\Models\Inspection;

class InspectionCertificatePdfController extends Controller
{
    public function __invoke(string $hash)
    {
        if (! auth('web')->check()) {
            return redirect()->route('login');
        }

        $inspection = Inspection::with(['client', 'inspectionObject', 'inspectable'])
            ->where('hash', $hash)
            ->firstOrFail();

        return (new InspectionCertificatePdf($inspection))->download();
    }
}
