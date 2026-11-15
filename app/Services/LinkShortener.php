<?php

namespace App\Services;

use App\Models\Link;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Random\Engine\Secure;
use Random\Randomizer;
use Throwable;

final class LinkShortener
{
    private const string ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private const int CODE_LENGTH = 4;

    private const int MAX_ATTEMPTS = 5;

    public function __construct(private readonly ReservedShortCodes $reservedCodes) {}

    /**
     * @throws Throwable
     */
    public function shorten(User $owner, string $url): Link
    {
        $randomizer = new Randomizer(new Secure);
        $reservedCodes = $this->reservedCodes->all();

        for ($attempt = 1; ; $attempt++) {
            try {
                return DB::transaction(function () use ($owner, $url, $randomizer, $reservedCodes) {
                    // Serialize creates for this owner so simultaneous submissions can reuse a link.
                    $owner->newQuery()->lockForUpdate()->findOrFail($owner->getKey());
                    $existing = $owner->links()->where('url', $url)->first();
                    if ($existing !== null) {
                        return $existing;
                    }

                    do {
                        $code = $randomizer->getBytesFromString(self::ALPHABET, self::CODE_LENGTH);
                    } while (in_array($code, $reservedCodes, true));

                    return $owner->links()->create(['url' => $url, 'short' => $code]);
                }, attempts: 3);
            } catch (UniqueConstraintViolationException $exception) {
                if ($attempt === self::MAX_ATTEMPTS) {
                    throw $exception;
                }
            }
        }
    }
}
