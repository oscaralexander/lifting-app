<?php

namespace App\Http\Controllers;

use App\Enums\SubmissionPdfType;
use App\Lib\SubmissionPdf;
use App\Models\Sticker;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionPdfController extends Controller
{
    public function __invoke(Request $request, string $stickerHash, string $hash, SubmissionPdfType $type)
    {
        if (! auth('web')->check()) {
            // Redirect to admin login page
            return redirect()->route('login');
        }

        $sticker = Sticker::where('hash', $stickerHash)->firstOrFail();
        $submission = Submission::where('hash', $hash)->firstOrFail();

        $pdf = new SubmissionPdf($submission, $sticker, $type);

        // return $pdf->view();
        return $pdf->download();
    }
}
