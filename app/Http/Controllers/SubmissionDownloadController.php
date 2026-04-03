<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionDownloadController extends Controller
{
    public function __invoke(Request $request)
    {
        $path = $request->input('path');
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (!auth('web')->check()) {
            // Redirect to admin login page
            return redirect()->route('auth.sign-in');
        }

        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        if (in_array($ext, ['gif', 'jpg', 'png', 'webp'])) {
            return response()->file(Storage::disk('local')->path($path));
        }

        return response()->download(Storage::disk('local')->path($path));
    }
}
