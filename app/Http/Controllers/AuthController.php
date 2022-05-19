<?php

namespace App\Http\Controllers;

use App\Models\Login;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use UA;

class AuthController extends Controller
{
    public function __construct()
    {
        $datas = new Controller;
        view()->share($datas->publicDatas());
    }

    public function login()
    {
        return view('auth.login');
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function loginPost(Request $request)
    {
        if (Auth::attempt([
                            'username' => $request->username,
                            'password' => $request->password
                          ]) || Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
            $result = UA::parse($request->server('HTTP_USER_AGENT'));
            $login = new Login;
            $login->ip_address = $_SERVER["REMOTE_ADDR"];
            $login->browser = $result->ua->family;
            $login->os = $result->os->family;
            $login->user_id = Auth::user()->id;
            $login->tarih = now();
            $login->save();
            //Giriş Kaydı
            toastr()->success('Başarıyla Giriş Yapıldı.', 'Hoşgeldin ' . Auth::user()->name . '!');

            return redirect()->route('dashboard');
        }
        toastr()->error('Kullanıcı Adı veya Şifre Hatalı!');

        return redirect()->route('login');
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
     * @param Request $req
     *
     * @return RedirectResponse
     */
    public function registerPost(Request $req)
    {
        $data = $req->validate([
                                 'name'     => 'alpha|min:2|required',
                                 'surname'  => 'alpha|min:2|required',
                                 'email'    => 'email|unique:users,email',
                                 'username' => 'alpha_dash|min:3|unique:users,username',
                                 'password' => 'min:4|confirmed'
                               ]);
        $user = new User;
        $user->name = $req->name;
        $user->surname = $req->surname;
        $user->email = $req->email;
        $user->username = $req->username;
        $user->password = bcrypt($req->password);
        $user->role = 3;
        $user->created_at = now();
        $user->updated_at = now();
        $user->save();
        Auth::attempt(['username' => $req->username, 'password' => $req->password]);
        $result = UA::parse($req->server('HTTP_USER_AGENT'));
        $login = new Login;
        $login->ip_address = $_SERVER["REMOTE_ADDR"];
        $login->browser = $result->ua->family;
        $login->os = $result->os->family;
        $login->user_id = Auth::user()->id;
        $login->tarih = now();
        $login->save();
        //Giriş Kaydı
        toastr()->success('Kayıt Başarılı!');

        return redirect()->route('dashboard');
    }

    /**
     * @return Application|Factory|View
     */
    public function ProfileIndex()
    {
        $profile = Auth::user();

        return view('auth.profile', compact('profile'));
    }

    /**
     * @param Request $req
     *
     * @return RedirectResponse
     */
    public function ProfilePasswordUpdate(Request $req)
    {
        $data = $req->validate([
                                 'password_old' => 'required',
                                 'password'     => 'required|confirmed|min:5'
                               ]);
        $user = User::whereUsername($req->username)->first();
        if (Hash::check($req['password_old'], $user->password)) {
            $user->password = Hash::make($req->password);
            $user->updated_at = now();
            $user->save();
            toastr()->success('Şifre Başarıyla Kaydedilmiştir.');

            return redirect()->route('profile.index');
        } else {
            toastr()->error('Mevcut Şifre Doğru Değil!');

            return redirect()->route('profile.index');
        }
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function userDelete(Request $request)
    {
        $user = User::whereUsername($request->username)->first();

        if ($user->role == 1) {
            $modC = User::whereRole(1)->count();
            if ($modC == 1) {
                toastr()->error('Sistemde En Az 1 Adet Yönetici Olmalıdır!');

                return redirect()->back();
            }
        }

        if (Hash::check($request->password_old, $user->password)) {
            $user->delete();
            toastr()->warning('Seni özleyeceğiz...');

            return redirect()->route('login');
        } else {
            toastr()->error('Şifre Doğru Değil!');

            return redirect()->route('profile.index');
        }
    }
}
