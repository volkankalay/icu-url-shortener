<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Models\Link;
use App\Models\Setting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

//  Dashboard and User Features
Route::middleware('LoginCheck')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/link', fn () => redirect(route('dashboard') . '#baglanti-olustur'))->name('url.create');
    Route::get('/dashboard/details/{link}', [DashboardController::class, 'linkShow'])->name('url.show');
    Route::post('/dashboard/link', [LinkController::class, 'store'])->name('url.create.post');
    Route::post('/dashboard/link/update', [LinkController::class, 'update'])->name('url.update.post');
    Route::get('/dashboard/links', [DashboardController::class, 'linkList'])->name('url.list');
    // EDIT USER PROFILE
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.index');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.pass');
    Route::post('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete');

    Route::get('/dashboard/all-links', [SettingsController::class, 'linksIndex'])
        ->can('viewAny', Link::class)->name('admin.links');

    // ADMINISTRATOR AREA
    Route::middleware('isAdmin')->group(function () {
        Route::get('/dashboard/settings', [SettingsController::class, 'settingsIndex'])
            ->name('admin.settings');
        Route::post('/dashboard/settings', [SettingsController::class, 'settingsUpdate'])
            ->name('admin.settings.update');
        Route::get('/dashboard/users', [SettingsController::class, 'usersIndex'])->name('admin.users');
        Route::post('/dashboard/users/suspend', [SettingsController::class, 'userSuspend'])
            ->name('admin.users.suspend');
        Route::get('/dashboard/users/trash', [SettingsController::class, 'usersTrash'])
            ->name('admin.users.trash');
        Route::post('/dashboard/users/unsuspend', [SettingsController::class, 'userUnsuspend'])
            ->name('admin.users.unsuspend');
        Route::post('/dashboard/users/rolechanger', [SettingsController::class, 'userRoleChange'])
            ->name('admin.users.rolechange');
        Route::post('/dashboard/all-links/delete', [SettingsController::class, 'linkDelete'])
            ->name('admin.links.delete');
    });

    Route::get('/logout', 'App\Http\Controllers\AuthController@logout')->name('logout');
});

//  User Login-Register
Route::middleware('isLogin')->group(function () {
    Route::middleware('LoginSetting')->group(function () {
        Route::get('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
    });
    Route::get('/register', [AuthController::class, 'register'])
        ->middleware('RegisterSetting')
        ->name('register');
    Route::post('/register', [AuthController::class, 'registerPost'])
        ->middleware('RegisterSetting')
        ->name('register.post');
});

// Homepage Views
Route::get('/', function () {
    App::setlocale(App::getLocale());
    $settings = Setting::first();

    return view('homepage', compact('settings'));
})->name('home');

// URL Redirection
Route::get('/{link}', [LinkController::class, 'redirectToDestination'])->name('direct');
