<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateShortCodeRequest;
use App\Models\Link;
use App\Services\LinkShortener;
use App\Support\VisitorOptions;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class LinkController extends Controller
{
    public function store(StoreLinkRequest $request, LinkShortener $shortener): RedirectResponse
    {
        $link = $shortener->shorten($request->user(), $request->validated('url'));

        if ($link->wasRecentlyCreated) {
            toastr()->success('Link Kısaltıldı!');
        } else {
            toastr()->info('Link Hesabınızda Zaten Bulunuyor!');
        }

        return redirect()->route('dashboard')->with('short_link', $link->short);
    }

    /**
     * @throws Throwable
     */
    public function redirectToDestination(Request $request, string $link): RedirectResponse
    {
        $destination = DB::transaction(function () use ($request, $link) {
            $shortLink = Link::where('short', $link)->lockForUpdate()->first();
            $target = $shortLink === null ? false : parse_url($shortLink->url);
            if (! isset($target['host']) || ! in_array(strtolower($target['scheme'] ?? ''), ['http', 'https'], true)) {
                return null;
            }

            $options = VisitorOptions::fromRequest($request);
            if ($options !== null) {
                $now = now()->toImmutable();
                $ip = $options->ip();
                $ipField = $options->ipv4 === null ? 'ipv6' : 'ipv4';
                $recorded = $shortLink->visitors()->withTrashed()
                    ->where('created_at', '>=', $now->startOfDay())
                    ->where('created_at', '<', $now->addDay()->startOfDay())
                    ->where(fn ($query) => $query->where('options->' . $ipField, $ip)->orWhere('options->ip', $ip))
                    ->exists();

                if (! $recorded) {
                    $visit = $shortLink->visitors()->make(['options' => $options]);
                    $visit->setCreatedAt($now)->setUpdatedAt($now)->save();
                }
            }
            $shortLink->increment('total_clicks');

            return $shortLink->url;
        }, attempts: 3);

        if ($destination === null) {
            toastr()->warning('Adres geçersiz veya kaldırılmış.', title: 'Adres Hatası');

            return redirect()->route('home');
        }

        return redirect()->away($destination);
    }

    public function update(UpdateShortCodeRequest $request): RedirectResponse
    {
        $link = Link::where('short', $request->validated('shortold'))->firstOrFail();
        abort_unless($request->user()->can('update', $link), 404);

        try {
            $link->update(['short' => $request->validated('shortnew')]);
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'shortnew' => __('validation.unique', ['attribute' => __('validation.attributes')['shortnew'] ?? 'shortnew']),
            ]);
        }

        toastr()->success('Adres başarıyla değiştirildi!');

        return redirect()->route('url.show', $link->short);
    }
}
