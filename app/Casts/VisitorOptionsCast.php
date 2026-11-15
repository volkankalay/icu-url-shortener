<?php

namespace App\Casts;

use App\Support\VisitorOptions;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/** @implements CastsAttributes<VisitorOptions, VisitorOptions> */
final class VisitorOptionsCast implements CastsAttributes
{
    public bool $withoutObjectCaching = true;

    public function get(Model $model, string $key, mixed $value, array $attributes): ?VisitorOptions
    {
        $data = is_string($value) ? Json::decode($value) : null;

        return is_array($data) ? VisitorOptions::fromArray($data) : null;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if (! $value instanceof VisitorOptions) {
            throw new InvalidArgumentException('Visitor options must be a VisitorOptions instance.');
        }

        return json_encode((object) $value->toArray(), JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
    }
}
