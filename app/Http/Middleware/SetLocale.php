<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Apply locale from session — runs AFTER session is started
        if (session()->has('locale') && in_array(session('locale'), ['ar', 'en'])) {
            App::setLocale(session('locale'));
        }

        return $next($request);
    }
}
