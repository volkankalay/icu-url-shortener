<?php

use Illuminate\Support\Facades\Route;
use App\Models\Setting;

//  $$$   $$$   $$$
//  Dashboard and User Features
Route::middleware('LoginCheck')->group(function(){
Route::get('/dashboard','App\Http\Controllers\DashboardController@index')->name('dashboard');

Route::get('/dashboard/link','App\Http\Controllers\DashboardController@linkCreate')->name('url.create');
Route::get('/dashboard/details/{link}','App\Http\Controllers\DashboardController@linkShow')->name('url.show');
Route::post('/dashboard/link','App\Http\Controllers\LinkController@short')->name('url.create.post');
Route::post('/dashboard/link/update','App\Http\Controllers\LinkController@shortChange')->name('url.update.post');
Route::get('/dashboard/links','App\Http\Controllers\DashboardController@linkList')->name('url.list');
//Profil Düzenleme Alanı
Route::get('/profile','App\Http\Controllers\AuthController@ProfileIndex')->name('profile.index');
Route::post('/profile/password','App\Http\Controllers\AuthController@ProfilePasswordUpdate')->name('profile.pass');
Route::post('/profile/delete','App\Http\Controllers\AuthController@userDelete')->name('profile.delete');

//Yönetici Özel Alanı
  Route::middleware('isAdmin')->group(function(){
  Route::get('/dashboard/settings','App\Http\Controllers\SettingsController@settingsIndex')->name('admin.settings');
  Route::post('/dashboard/settings','App\Http\Controllers\SettingsController@settingsUpdate')->name('admin.settings.update');
  Route::get('/dashboard/users','App\Http\Controllers\SettingsController@usersIndex')->name('admin.users');
  Route::post('/dashboard/users/suspend','App\Http\Controllers\SettingsController@userSuspend')->name('admin.users.suspend');
  Route::get('/dashboard/users/trash','App\Http\Controllers\SettingsController@usersTrash')->name('admin.users.trash');
  Route::post('/dashboard/users/unsuspend','App\Http\Controllers\SettingsController@userUnsuspend')->name('admin.users.unsuspend');
  Route::post('/dashboard/users/rolechanger','App\Http\Controllers\SettingsController@userRoleChange')->name('admin.users.rolechange');
  Route::post('/dashboard/users/force','App\Http\Controllers\SettingsController@userForceDelete')->name('admin.users.forceDelete');
  Route::get('/dashboard/all-links','App\Http\Controllers\SettingsController@linksIndex')->name('admin.links');
  Route::post('/dashboard/all-links/force','App\Http\Controllers\SettingsController@linksForceDelete')->name('admin.links.force');
  });
//Yönetici Özel Alanı Sonu
Route::get('/logout','App\Http\Controllers\AuthController@logout')->name('logout');
});

//  $$$   $$$   $$$
//  User Login-Register
Route::middleware('isLogin')->group(function(){
Route::get('/login','App\Http\Controllers\AuthController@login')->name('login');
Route::post('/login','App\Http\Controllers\AuthController@loginPost')->name('login.post');
Route::get('/register','App\Http\Controllers\AuthController@register')->middleware('RegisterSetting')->name('register');
Route::post('/register','App\Http\Controllers\AuthController@registerPost')->middleware('RegisterSetting')->name('register.post');
});

//    $$$
// Homepage Views
//    $$$
Route::get('/',function(){
  App::setlocale(App::getLocale());
  $settings = Setting::first();
  return view('homepage',compact('settings'));
})->name('home');
//END HOMEPAGE
Route::get('/{link}','App\Http\Controllers\LinkController@route')->name('direct');
//ROUTE SHORT LINKS
