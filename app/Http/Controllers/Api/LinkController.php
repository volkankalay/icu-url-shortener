<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreLinkRequest;
use App\Models\Setting;
use App\Services\LinkShortener;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LinkController
{
    /**
     * @throws Throwable
     */
    public function store(StoreLinkRequest $request, LinkShortener $shortener): JsonResponse
    {
        $link = $shortener->shorten($request->user(), $request->validated('url'));
        $site = Setting::first()?->site ?: config('app.url');

        return response()
            ->json([
                'success'   => true,
                'short_url' => rtrim($site, '/') . '/' . $link->short,
            ], Response::HTTP_CREATED);
    }
}
