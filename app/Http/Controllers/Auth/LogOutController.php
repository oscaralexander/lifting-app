<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LogOutController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        auth('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
}
