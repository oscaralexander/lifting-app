<?php

namespace App\Http\Controllers;

use App\Lib\InspectionCertificatePdf;
use App\Models\Inspection;

class InspectionCertificateHtmlController extends Controller
{
    public function __invoke(string $hash)
    {
        if (! auth('web')->check()) {
            return redirect()->route('login');
        }

        $inspection = Inspection::with(['client', 'inspectionObject', 'inspectable'])
            ->where('hash', $hash)
            ->firstOrFail();

        return response((new InspectionCertificatePdf($inspection))->html(), 200, [
            'Content-Type' => 'text/html',
        ]);
    }
}
