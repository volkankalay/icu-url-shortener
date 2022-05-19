<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Login;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use UA;

class DashboardController extends Controller
{
    public function __construct()
    {
        $datas = new Controller;
        view()->share($datas->publicDatas());
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $bu = Login::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        if ($bu->count() > 1) {
            $lastlogin = Login::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->skip(1)->first();
        } else {
            $lastlogin = Login::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
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

        return view('dashboard.welcome', compact('lastlogin', 'linkViewCount', 'todayCount', 'thisMonthCount'));
    }

    /**
     * @return Application|Factory|View
     */
    public function linkCreate()
    {
        return view('dashboard.linkCreate');
    }

    /**
     * @return Application|Factory|View
     */
    public function linkList()
    {
        $links = Link::whereOwner(Auth::user()->id)->get();

        return view('dashboard.linkList', compact('links'));
    }

    /**
     * @param $link
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function linkShow($link)
    {
        if (Auth::user()->role == 1) {
            $url = Link::whereShort($link)->first();
        } else {
            $url = Link::whereShort($link)->whereOwner(Auth::user()->id)->first();
        }
        if ($url == null) {
            toastr()->error('İstenen Link Bulunamadı.', 'Hata');

            return redirect()->route('url.list');
        }
        $visitors = $url->getVisitors();
        $visitorCount = $url->getVisitors()->count();
        $visitorCountX = $url->getVisitors()->groupBy('xsrf_token')->count();

        $today = Carbon::today();
        $dailyCount = $url->getVisitors()
                          ->whereBetween('tarih', [$today, now()])
                          ->count();
        $dailyCountX = $url->getVisitors()
                           ->whereBetween('tarih', [$today, now()])
                           ->groupBy('xsrf_token')
                           ->count();
        $month = $today->startOfMonth();
        $monthlyCount = $url->getVisitors()
                            ->whereBetween('tarih', [$month, now()])
                            ->count();
        $monthlyCountX = $url->getVisitors()
                             ->whereBetween('tarih', [$month, now()])
                             ->groupBy('xsrf_token')
                             ->count();

        $favBrowser = 'No Data';
        $favBrowserCount = 0;

        $browsers = Visitor::select('browser')
                           ->where('link_id', $url->id)
                           ->groupBy('browser')
                           ->get();
        foreach ($browsers as $browser) {
            $browserCount = Visitor::where(['link_id' => $url->id, 'browser' => $browser->browser])->count();
            if ($browserCount > $favBrowserCount) {
                $favBrowserCount = $browserCount;
                $favBrowser = $browser->browser;
            }
        }
        $favOS = 'No Data';
        $favOSCount = 0;

        $OSS = Visitor::select('os')->where('link_id', $url->id)->groupBy('os')->get();
        foreach ($OSS as $OS) {
            $OSCount = Visitor::where(['link_id' => $url->id, 'os' => $OS->os])->count();
            if ($OSCount > $favOSCount) {
                $favOSCount = $OSCount;
                $favOS = $OS->os;
            }
        }

        return view('dashboard.linkShow', compact(
          'url', 'visitors', 'favBrowser', 'favOS', 'browsers', 'OSS',
          'visitorCount', 'dailyCount', 'monthlyCount',
          'visitorCountX', 'dailyCountX', 'monthlyCountX'
        ));
    }
}
