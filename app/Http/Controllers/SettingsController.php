<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Login;
use App\Models\Link;
use App\Models\Visitor;
use App\Models\Setting;
use UA;

class SettingsController extends Controller
{
    public function __construct(){
    $datas = new Controller;
      view()->share($datas->publicDatas());
    }
    public function settingsIndex(){
      return view('admin.settings');
    }
    public function settingsUpdate(Request $request){
      $request->validate([
        'logo'=>'image|max:2048',
        'favicon'=>'image|max:2048'
      ]);
      //dd($request->register);
      $settings               =  Setting::first();
      $settings->site         =  $request->site;
      $settings->title        =  $request->title;
      $settings->keywords     =  $request->keywords;
      $settings->header_code  =  $request->header_code;
      $settings->footer_code  =  $request->footer_code;
      $settings->updated_at   =  now();

      if($request->register!=NULL){
        $settings->register   = 1;
      }
      else{
        $settings->register   = 0;
      }

      if($request->hasFile('logo')){
        $logoName         = 'logo'.'.'.$request->logo->getClientOriginalExtension();
        $request->logo->move(public_path('img'),$logoName);
        $settings->logo     = asset('img/').'/'.$logoName;
      }
      if($request->hasFile('favicon')){
        $faviconName         = 'favicon'.'.'.$request->favicon->getClientOriginalExtension();
        $request->favicon->move(public_path('img'),$faviconName);
        $settings->favicon     = asset('img/').'/'.$faviconName;
      }
      $settings->save();
      toastr()->success('Ayarlar Kaydedildi!');
      return redirect()->route('admin.settings');
    }
    public function usersIndex(){
      $users  = User::all();
      return view('admin.users',compact('users'));
    }
    public function linksIndex(){
      $links  = Link::all();
      return view('admin.links',compact('links'));
    }
    public function userSuspend(Request $req){
      $user = User::findOrFail($req->user);
      $user->updated_at=now();
      $user->delete();
      toastr()->success('Hesap Donduruldu!');
      return redirect()->back();
    }
    public function usersTrash(){
      $users  = User::onlyTrashed()->get();
      return view('admin.userstrashed',compact('users'));
    }
    public function userUnsuspend(Request $req){
      $user = User::withTrashed()->findOrFail($req->user);
      $user->deleted_at=null;
      $user->updated_at=now();
      $user->save();
      toastr()->success('Hesap Aktif Edildi!');
      return redirect()->back();
    }
    public function userForceDelete(Request $req){
      $id = $req->user;                                                         //  ID yakala
      $links  = Link::whereOwner($id)->get();                                   //  Üyenin Oluşturduğu Linkleri Bul
      $logins = Login::where('user_id',$id)->get();                             //  Kullanıcı Giriş Kayıtları

      foreach ($links as $adres) {
          $ziyaretciler = $adres->getVisitors();                                //  Bulunan linkin ziyaretçi kayıtlarını al.
          foreach ($ziyaretciler as $ziyaret) {
            $ziyaret->delete();                                                 //  Ziyaretçi verilerini temizle.
          }
      }
      foreach ($links as $adres) {
        $adres->delete();                                                       //  Adres verilerini temizle (Linkler)
      }
      foreach ($logins as $login) {
        $login->delete();                                                       //  Kullanıcının Giriş Kayıtlarını Sil.
      }
      $user = User::withTrashed()->find($id);
      $user->forceDelete();                                                     //  Kullanıcıyı veritabanından kaldır.

      toastr()->warning('Kullanıcı ve verileri sistemden silindi.');
      return redirect()->back();
    }
    public function linksForceDelete(Request $req){
      $adres = Link::findOrFail($req->link);                                    //  gelen short link id
      $ziyaretciler = $adres->getVisitors();                                    //  linkin ziyaretçi kayıtlarını al.
      foreach ($ziyaretciler as $ziyaret) {
        $ziyaret->delete();                                                     //  Ziyaretçi verilerini temizle.
      }
      $adres->delete();
      toastr()->warning('Adres Silindi!');
      return redirect()->back();
    }
    public function userRoleChange(Request $req){
      $user             = User::whereUsername($req->username)->first();
      if($user->role == $req->yetki){
          toastr()->warning('Yetki Değişimi Yok.');
          return redirect()->back();                                             // yetki değişmemiş ise güncelleme yapmadan geri dön
       }
      $user->role       = $req->yetki;
      $user->updated_at = now();
      $user->save();
      toastr()->success('Yetki Değişimi Tamamlandı.',$user->username);
      return redirect()->back();
    }
}
