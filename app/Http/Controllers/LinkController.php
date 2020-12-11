<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Login;
use App\Models\Link;
use App\Models\Visitor;
use UA;

class LinkController extends Controller
{
    public function __construct(){
    $datas = new Controller;
      view()->share($datas->publicDatas());
    }

    public function generateShortUrl(){
      $result = base_convert(rand(1000,99999), 10, 36);                         //short link creator

      $dangerLinks = ['dashboard','login','register','logout'];                 //Dissallow link types!
        foreach ($dangerLinks as $dl) {
          if($result == $dl){
            $this->generateShortUrl();                                          // if exist shorten link repeat again.
          }
        }
      $data = Link::whereUrl($result)->first();
        if($data != null){
          $this->generateShortUrl();                                            // if exist shorten link repeat again.
        }
        return $result;
    }

    public function short(Request $req){
      $data = $req->validate([
        'url'=>'url',
        'user'=>'integer|exists:users,id'
      ]);
      $check = Link::where('url',$req->url)->where('owner',$req->user)->first();
        if($check==null){
          $short = $this->generateShortUrl(); //url shortener
            $url        =   new Link;
            $url->url   =   $req->url;
            $url->short =   $short;
            $url->owner =   $req->user;
            $url->save();
            $son        =   $url->short;
          toastr()->success('Link Kısaltıldı!');
        } else{
            $son        =   $check->short;
          toastr()->info('Link Hesabınızda Zaten Bulunuyor!');
        }
        return redirect()->route('url.create')->withErrors($son);
      }

      public function route(Request $request, $link){
        $checkLink  = Link::whereShort($link)->first();
        if($checkLink==null){
          toastr()->warning('Adres geçersiz veya kaldırılmış.','Adres Hatası');
           return redirect()->route('home');
         }                                                                      // is link exists?
        $xsrf       = $request->cookie('XSRF-TOKEN');
        $result            =  UA::parse($request->server('HTTP_USER_AGENT')); //Visitor's info
        $links             =  Link::whereShort($link)->first();               //Find link id
          $visitor             =  new Visitor;
          $visitor->ip_address =  $_SERVER["REMOTE_ADDR"];
          $visitor->browser    =  $result->ua->family;
          $visitor->os         =  $result->os->family;
          $visitor->link_id    =  $links->id;
          $visitor->xsrf_token =  $xsrf;                                    // Disabled for development!
          $visitor->tarih      =  now();
          $visitor->save();
        $go = Link::whereShort($link)->first();
        return redirect($go->url);                                              //go original address
      }

      public function shortChange(Request $req){
        if($req->shortold == $req->shortnew){
          toastr()->error('Yeni adres eskisiyle aynı olamaz!');
          return redirect()->route('url.list');
        }
        $data = $req->validate([
          'shortold'=>'alpha_dash',
          'shortnew'=>'alpha_dash|unique:links,short'
        ]);
        $link = Link::where('short',$req->shortold)->first();
        $link->short  = $req->shortnew;
        $link->save();
        toastr()->success('Adres başarıyla değiştirildi!');
        return redirect()->route('url.list');
        }

}
