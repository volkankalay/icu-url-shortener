<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use UA;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    /**
     * @return RedirectResponse
     */
    public function loginPost(Request $request)
    {
        $data = $request->validate([
            'username'   => 'required|string|max:191',
            'password'   => 'required|string',
            'rememberme' => 'sometimes|boolean',
        ]);

        $identity = str_contains($data['username'], '@') ? 'email' : 'username';
        if (! Auth::attempt([$identity => $data['username'], 'password' => $data['password']], $request->boolean('rememberme'))) {
            throw ValidationException::withMessages(['username' => __('auth.failed')]);
        }

        $request->session()->regenerate();
        $this->recordLogin($request, $request->user());
        toastr()->escapeHtml()->success('Başarıyla Giriş Yapıldı.', title: 'Hoşgeldin ' . $request->user()->name . '!');

        return redirect()->route('dashboard');
    }

    /**
     * @return RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        toastr()->success('Çıkış Yapıldı!');

        return redirect()->route('home');
    }

    /**
     * @return Application|Factory|View
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * @return RedirectResponse
     */
    public function registerPost(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|min:2|max:191',
            'surname'  => 'required|string|min:2|max:191',
            'email'    => 'required|email|max:191|unique:users,email',
            'username' => 'required|alpha_dash|min:3|max:191|unique:users,username',
            'password' => 'required|string|min:8|max:72|confirmed',
        ]);
        if (strlen($data['password']) > 72 || str_contains($data['password'], "\0")) {
            throw ValidationException::withMessages(['password' => __('auth.password_invalid')]);
        }

        $user = DB::transaction(function () use ($request, $data) {
            $user = User::create($data);
            $this->recordLogin($request, $user);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();
        toastr()->success('Kayıt Başarılı!');

        return redirect()->route('dashboard');
    }

    private function recordLogin(Request $request, User $user): void
    {
        $result = UA::parse($request->userAgent() ?? '');
        $user->logins()->create([
            'ip_address' => $request->ip(),
            'browser'    => $result->ua->family,
            'os'         => $result->os->family,
            'tarih'      => now(),
        ]);
    }
}
