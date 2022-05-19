<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Login;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use UA;

class SettingsController extends Controller
{
    public function __construct()
    {
        $datas = new Controller;
        view()->share($datas->publicDatas());
    }

    /**
     * @return Application|Factory|View
     */
    public function settingsIndex()
    {
        return view('admin.settings');
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function settingsUpdate(Request $request)
    {
        $request->validate([
                             'logo'    => 'image|max:2048',
                             'favicon' => 'image|max:2048'
                           ]);
        $settings = Setting::first();
        $settings->site = $request->site;
        $settings->title = $request->title;
        $settings->keywords = $request->keywords;
        $settings->header_code = $request->header_code;
        $settings->footer_code = $request->footer_code;
        $settings->updated_at = now();

        if ($request->register != null) {
            $settings->register = 1;
        } else {
            $settings->register = 0;
        }

        if ($request->hasFile('logo')) {
            $logoName = 'logo' . '.' . $request->logo->getClientOriginalExtension();
            $request->logo->move(public_path('img'), $logoName);
            $settings->logo = asset('img/') . '/' . $logoName;
        }
        if ($request->hasFile('favicon')) {
            $faviconName = 'favicon' . '.' . $request->favicon->getClientOriginalExtension();
            $request->favicon->move(public_path('img'), $faviconName);
            $settings->favicon = asset('img/') . '/' . $faviconName;
        }
        $settings->save();
        toastr()->success('Ayarlar Kaydedildi!');

        return redirect()->route('admin.settings');
    }

    /**
     * @return Application|Factory|View
     */
    public function usersIndex()
    {
        $users = User::all();

        return view('admin.users', compact('users'));
    }

    /**
     * @return Application|Factory|View
     */
    public function linksIndex()
    {
        $links = Link::all();

        return view('admin.links', compact('links'));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function userSuspend(Request $request)
    {
        $user = User::findOrFail($request->user);
        $user->updated_at = now();
        $user->delete();
        toastr()->success('Hesap Donduruldu!');

        return redirect()->back();
    }

    /**
     * @return Application|Factory|View
     */
    public function usersTrash()
    {
        $users = User::onlyTrashed()->get();

        return view('admin.userstrashed', compact('users'));
    }

    /**
     * @param Request $req
     *
     * @return RedirectResponse
     */
    public function userUnsuspend(Request $req)
    {
        $user = User::withTrashed()->findOrFail($req->user);
        $user->deleted_at = null;
        $user->updated_at = now();
        $user->save();
        toastr()->success('Hesap Aktif Edildi!');

        return redirect()->back();
    }

    /**
     * @param Request $req
     *
     * @return RedirectResponse
     */
    public function userForceDelete(Request $req)
    {
        $id = $req->user;
        $links = Link::whereOwner($id)->get();
        $logins = Login::where('user_id', $id)->get();

        foreach ($links as $adres) {
            $ziyaretciler = $adres->getVisitors();
            foreach ($ziyaretciler as $ziyaret) {
                $ziyaret->delete();
            }
        }
        foreach ($links as $adres) {
            $adres->delete();
        }
        foreach ($logins as $login) {
            $login->delete();
        }
        $user = User::withTrashed()->find($id);
        $user->forceDelete();

        toastr()->warning('Kullanıcı ve verileri sistemden silindi.');

        return redirect()->back();
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function linksForceDelete(Request $request)
    {
        $adres = Link::findOrFail($request->link);
        $visitors = $adres->getVisitors();
        foreach ($visitors as $visit) {
            $visit->delete();
        }
        $adres->delete();
        toastr()->warning('Adres Silindi!');

        return redirect()->back();
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function userRoleChange(Request $request)
    {
        $user = User::whereUsername($request->username)->first();
        if ($user->role == $request->yetki) {
            toastr()->warning('Yetki Değişimi Yok.');

            return redirect()->back();
        }
        $user->role = $request->yetki;
        $user->updated_at = now();
        $user->save();
        toastr()->success('Yetki Değişimi Tamamlandı.', $user->username);

        return redirect()->back();
    }
}
