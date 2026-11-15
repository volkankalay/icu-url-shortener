<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class RegisterSetting
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $settings = Setting::first();
        if ($settings->register != 1) {
            toastr()->error('Kayıt İşlemi Kısıtlandı.');

            return redirect()->route('home');
        }

        return $next($request);
    }
}
