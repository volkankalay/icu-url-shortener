<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Requests\UpdateSiteSettingsRequest;
use App\Models\Link;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function settingsIndex()
    {
        return view('admin.settings');
    }

    public function settingsUpdate(UpdateSiteSettingsRequest $request): RedirectResponse
    {
        $settings = Setting::firstOrFail();
        $settings->fill($request->safe()->except(['logo', 'favicon']));

        foreach (['logo', 'favicon'] as $field) {
            if ($file = $request->file($field)) {
                $name = $field . '.' . $file->extension();
                $file->move(public_path('img'), $name);
                $settings->{$field} = asset('img/' . $name);
            }
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

        return view('admin.users', ['users' => $users, 'suspended' => false]);
    }

    /**
     * @return Application|Factory|View
     */
    public function linksIndex()
    {
        $links = Link::with('owner')->withCount('visitors')->get();

        return view('admin.links', compact('links'));
    }

    /**
     * @return RedirectResponse
     */
    public function userSuspend(Request $request)
    {
        $user = User::findOrFail($request->user);
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

        return view('admin.users', ['users' => $users, 'suspended' => true]);
    }

    /**
     * @return RedirectResponse
     */
    public function userUnsuspend(Request $req)
    {
        User::onlyTrashed()->findOrFail($req->user)->restore();
        toastr()->success('Hesap Aktif Edildi!');

        return redirect()->back();
    }

    /**
     * @return RedirectResponse
     */
    public function linkDelete(Request $request)
    {
        $data = $request->validate(['link' => 'required|integer']);
        DB::transaction(function () use ($data) {
            Link::query()->lockForUpdate()->findOrFail($data['link'])->delete();
        }, attempts: 3);
        toastr()->warning('Kısa bağlantı devre dışı bırakıldı.');

        return redirect()->back();
    }

    /**
     * @return RedirectResponse
     */
    public function userRoleChange(Request $request)
    {
        $data = $request->validate([
            'username'  => ['required', 'string', 'max:191'],
            'user_type' => ['bail', 'required', 'string', Rule::enum(UserType::class)],
        ]);
        $user = User::whereUsername($data['username'])->firstOrFail();
        $userType = UserType::from($data['user_type']);
        if ($user->user_type === $userType) {
            toastr()->warning('Yetki Değişimi Yok.');

            return redirect()->back();
        }
        $user->user_type = $userType;
        $user->save();
        toastr()->escapeHtml()->success('Yetki Değişimi Tamamlandı.', title: $user->username);

        return redirect()->back();
    }
}
