<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\IssueApiTokenRequest;
use App\Models\Setting;
use App\Models\User;
use App\Services\ApiTokens;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Timebox;

class TokenController
{
    public function store(IssueApiTokenRequest $request, ApiTokens $tokens, Timebox $timebox): JsonResponse
    {
        abort_unless(Setting::first()?->login, 403);

        $credentials = $request->validated();
        $field = isset($credentials['email']) ? 'email' : 'username';
        $user = $timebox->call(function () use ($field, $credentials) {
            $user = User::where($field, $credentials[$field])->first();

            return $user && Hash::check($credentials['password'], $user->getAuthPassword()) ? $user : null;
        }, 200_000);

        abort_if($user === null, 401, __('auth.failed'));

        return response()->json([
            'access_token' => $tokens->issue($user),
            'token_type'   => 'Bearer',
            'expires_in'   => ApiTokens::LIFETIME,
        ])->header('Cache-Control', 'no-store');
    }
}
