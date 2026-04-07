<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Settings;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function __invoke(Request $request)
    {
        // Get filename from path parameter
        $filename = pathinfo($request->input('path'), PATHINFO_BASENAME);

        // Find document
        $document = Document::where('filename', $filename)->firstOrFail();

        // Check if user is allowed to download this document
        $passcode = Settings::where('key', 'passcode')->firstOrFail()->value;
        $sessionPasscode = session()->get('passcode');

        if (! auth('web')->user() && $sessionPasscode !== $passcode) {
            // Redirect to admin login page
            return redirect()->route('login');
        }

        // View document
        if ($request->input('view') === '1') {
            return response()->file($document->path);
        }

        // Download document
        if (is_readable($document->path)) {
            return response()->download($document->path, $document->filename_orig);
        }

        abort(404);
    }
}
