<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Visitor;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use UA;

class LinkController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $datas = new Controller;
        view()->share($datas->publicDatas());
    }

    /**
     * @return string
     */
    public function generateShortUrl()
    {
        $result = base_convert(rand(1000, 99999), 10, 36);

        $dangerLinks = ['dashboard', 'login', 'register', 'logout'];
        foreach ($dangerLinks as $dl) {
            if ($result == $dl) {
                $this->generateShortUrl();
            }
        }
        $data = Link::whereUrl($result)->first();
        if ($data != null) {
            $this->generateShortUrl();
        }

        return $result;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function short(Request $request)
    {
        $data = $request->validate([
                                     'url'  => 'url',
                                     'user' => 'integer|exists:users,id'
                                   ]);
        $check = Link::where('url', $request->url)->where('owner', $request->user)->first();
        if ($check == null) {
            $short = $this->generateShortUrl(); //url shortener
            $url = new Link;
            $url->url = $request->url;
            $url->short = $short;
            $url->owner = $request->user;
            $url->save();
            $son = $url->short;
            toastr()->success('Link Kısaltıldı!');
        } else {
            $son = $check->short;
            toastr()->info('Link Hesabınızda Zaten Bulunuyor!');
        }

        return redirect()->route('url.create')->withErrors($son);
    }

    /**
     * @param Request $request
     * @param         $link
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function route(Request $request, $link)
    {
        $checkLink = Link::whereShort($link)->first();
        if ($checkLink == null) {
            toastr()->warning('Adres geçersiz veya kaldırılmış.', 'Adres Hatası');

            return redirect()->route('home');
        }                                                                      // is link exists?
        $xsrf = $request->cookie('XSRF-TOKEN');
        $result = UA::parse($request->server('HTTP_USER_AGENT')); //Visitor's info
        $links = Link::whereShort($link)->first();               //Find link id

        $visitor = new Visitor;
        $visitor->ip_address = $_SERVER["REMOTE_ADDR"];
        $visitor->browser = $result->ua->family;
        $visitor->os = $result->os->family;
        $visitor->link_id = $links->id;
        $visitor->xsrf_token = $xsrf;                                    // Disabled for development!
        $visitor->tarih = now();
        $visitor->save();
        $go = Link::whereShort($link)->first();

        return redirect($go->url);                                              //go original address
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function shortChange(Request $request)
    {
        if ($request->shortold == $request->shortnew) {
            toastr()->error('Yeni adres eskisiyle aynı olamaz!');

            return redirect()->route('url.list');
        }
        $data = $request->validate([
                                     'shortold' => 'alpha_dash',
                                     'shortnew' => 'alpha_dash|unique:links,short'
                                   ]);
        $link = Link::where('short', $request->shortold)->first();
        $link->short = $request->shortnew;
        $link->save();
        toastr()->success('Adres başarıyla değiştirildi!');

        return redirect()->route('url.list');
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function checkUrl(Request $request)
    {
        $request->validate([
                             'url' => 'string|required|max:255|min:2',
                           ]);
        $url = Str::of($request->input('url'))->trim();
        $response = false;
        if (!is_null($url)) {
            $link = Link::where('short', '=', $url)->first();
            if ($link == null) {
                $response = true;
            }
        }

        return response()->json([
                                  'success' => $response,
                                  'message' => 'URL ALREADY EXISTS',
                                ]);
    }

}
