<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

final class ApiTokens
{
    public const int LIFETIME = 3600;

    public function issue(User $user): string
    {
        $token = bin2hex(random_bytes(32));

        abort_unless(Cache::put($this->key($token), [
            'user_id'              => $user->id,
            'password_fingerprint' => hash('sha256', $user->getAuthPassword()),
        ], self::LIFETIME), 503);

        return $token;
    }

    public function resolve(?string $token): ?User
    {
        if ($token === null || ! preg_match('/\A[0-9a-f]{64}\z/', $token)) {
            return null;
        }

        $record = Cache::get($this->key($token));
        if ($record === null) {
            return null;
        }

        $user = User::find($record['user_id']);

        return $user !== null && hash_equals($record['password_fingerprint'], hash('sha256', $user->getAuthPassword()))
            ? $user
            : null;
    }

    private function key(string $token): string
    {
        return 'api:token:' . hash('sha256', $token);
    }
}
