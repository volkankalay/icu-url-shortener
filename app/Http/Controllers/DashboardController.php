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
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct(){
      $datas = new Controller;
      view()->share($datas->publicDatas());
    }
    public function index(){
      $bu = Login::where('user_id',Auth::user()->id)->orderBy('id','desc')->get();
      if($bu->count()>1){
        $lastlogin  = Login::where('user_id',Auth::user()->id)->orderBy('id','desc')->skip(1)->first();
      } else{
        $lastlogin  = Login::where('user_id',Auth::user()->id)->orderBy('id','desc')->first();
      }
      $linkleri = Link::whereOwner(Auth::user()->id)->get();
      $linkViewCount = 0;
      $todayCount = 0;
      $thisMonthCount = 0;
      foreach ($linkleri as $link) {
        $linkViewCount += $link->getVisitors()->count();
        $todayCount += $link->getTodayCount();
        $thisMonthCount += $link->getMonthCount();
      }
      return view('dashboard.welcome',compact('lastlogin','linkViewCount','todayCount','thisMonthCount'));
    }
    public function linkCreate(){
      return view('dashboard.linkCreate');
    }
    public function linkList(){
      $links  = Link::whereOwner(Auth::user()->id)->get();
      return view('dashboard.linkList',compact('links'));
    }
    public function linkShow($link){
      if(Auth::user()->role==1){
        $url =  Link::whereShort($link)->first();
      }else{
        $url =  Link::whereShort($link)->whereOwner(Auth::user()->id)->first();
      }
      if($url==null){
        toastr()->error('İstenen Link Bulunamadı.','Hata');
        return redirect()->route('url.list');
      }
      $visitors       =   $url->getVisitors();
      $visitorCount   =   $url->getVisitors()->count();
      $visitorCountX  =   $url->getVisitors()->groupBy('xsrf_token')->count();

      $today  = Carbon::today();
      $dailyCount   = $url->getVisitors()->whereBetween('tarih',[$today,now()])->count();                         //Günlük Ziyaretçi Çoğul
      $dailyCountX  = $url->getVisitors()->whereBetween('tarih',[$today,now()])->groupBy('xsrf_token')->count();  //Günlük Ziyaretçi Tekil
      $month        = $today->startOfMonth();
      $monthlyCount = $url->getVisitors()->whereBetween('tarih',[$month,now()])->count();                         //Aylık Ziyaretçi Çoğul
      $monthlyCountX = $url->getVisitors()->whereBetween('tarih',[$month,now()])->groupBy('xsrf_token')->count(); //Aylık Ziyaretçi Tekil

      $favBrowser='No Data';
      $favBrowserCount=0;

      $browsers  =  Visitor::select('browser')->where('link_id',$url->id)->groupBy('browser')->get();             //Favori Tarayıcı Bulma
        foreach($browsers as $browser){
          $browserCount = Visitor::where(['link_id'=>$url->id,'browser'=>$browser->browser])->count();
            if($browserCount > $favBrowserCount){
              $favBrowserCount = $browserCount;
              $favBrowser      = $browser->browser;
            }
        }
                                                                                //Favori İşletim Sistemi Bulma
      $favOS='No Data';
      $favOSCount=0;

      $OSS  =  Visitor::select('os')->where('link_id',$url->id)->groupBy('os')->get();
        foreach($OSS as $OS){
          $OSCount = Visitor::where(['link_id'=>$url->id,'os'=>$OS->os])->count();
            if($OSCount > $favOSCount){
              $favOSCount = $OSCount;
              $favOS      = $OS->os;
            }
        }

      return view('dashboard.linkShow',compact(
        'url','visitors','favBrowser','favOS','browsers','OSS',
        'visitorCount','dailyCount','monthlyCount',
        'visitorCountX','dailyCountX','monthlyCountX'
      ));                                                                       // X olanlar tekil girişleri temsil eder!
    }
}
