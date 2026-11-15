<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Services\DashboardReports;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, DashboardReports $reports): View
    {
        return view('dashboard.welcome', $reports->overview($request->user()));
    }

    public function linkList(Request $request): View
    {
        return view('dashboard.linkList', ['links' => $request->user()->links()->withCount('visitors')->get()]);
    }

    public function linkShow(Request $request, string $link, DashboardReports $reports): View|RedirectResponse
    {
        $user = $request->user();
        $shortLink = Link::where('short', $link)->first();

        if ($shortLink === null || $user->cannot('view', $shortLink)) {
            toastr()->error('İstenen Link Bulunamadı.', title: 'Hata');

            return redirect()->route('url.list');
        }

        return view('dashboard.linkShow', $reports->linkDetails($shortLink));
    }
}
