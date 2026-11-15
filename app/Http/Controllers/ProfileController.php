<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Requests\DeleteProfileRequest;
use App\Http\Requests\UpdateProfilePasswordRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $profile = $request->user();

        return view('auth.profile', ['profile' => $profile, 'linkCount' => $profile->linkCount()]);
    }

    public function updatePassword(UpdateProfilePasswordRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->update(['password' => $request->validated('password')]);
        $request->session()->regenerate();
        toastr()->success(__('auth.password_updated'));

        return redirect()->route('profile.index');
    }

    public function destroy(DeleteProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $deleted = DB::transaction(function () use ($user): bool {
            if ($user->isAdmin() && User::where('user_type', UserType::ADMIN->value)
                ->orderBy('id')->lockForUpdate()->pluck('id')->count() <= 1) {
                return false;
            }

            return (bool) $user->delete();
        }, attempts: 3);

        if (! $deleted) {
            toastr()->error(__('auth.last_admin'));

            return redirect()->route('profile.index');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        toastr()->success(__('auth.account_deleted'));

        return redirect()->route('login');
    }
}
