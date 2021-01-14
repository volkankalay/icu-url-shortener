<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;

class RegisterSetting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $settings = Setting::first();
        if($settings->register!=1){
          toastr()->error('Kayıt İşlemi Kısıtlandı.');
          return redirect()->route('home');
        }
        return $next($request);
    }
}
