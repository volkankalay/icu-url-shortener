<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::user()?->isAdmin()) {
            toastr()->error('Yetkilendirme Hatası.');

            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
