<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property      int                      $id
 * @property      int                      $owner_id
 * @property      string                   $url
 * @property      string                   $short
 * @property      int                      $total_clicks
 * @property      Carbon|null              $created_at
 * @property      Carbon|null              $updated_at
 * @property      Carbon|null              $deleted_at
 * @property-read User|null                $owner
 * @property-read Collection<int, Visitor> $visitors
 * @property-read int|null                 $visitors_count
 */
class Link extends Model
{
    use SoftDeletes;

    protected $fillable = ['owner_id', 'url', 'short'];

    protected function casts(): array
    {
        return [
            'owner_id'     => 'integer',
            'total_clicks' => 'integer',
        ];
    }

    public function getDate(): ?string
    {
        return $this->created_at?->locale('tr_TR')->isoFormat('lll');
    }

    /** @return HasMany<Visitor, $this> */
    public function visitors(): HasMany
    {
        return $this->hasMany(Visitor::class);
    }

    public function getTodayCount(): int
    {
        $now = now();

        return $this->visitors()->whereBetween('created_at', [$now->copy()->startOfDay(), $now])->count();
    }

    public function getMonthCount(): int
    {
        $now = now();

        return $this->visitors()->whereBetween('created_at', [$now->copy()->startOfMonth(), $now])->count();
    }

    public function visitorCount(): int
    {
        return $this->visitors_count ?? $this->visitors()->count();
    }

    /** @return BelongsTo<User, $this> */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id')->withTrashed();
    }

    protected static function boot(): void
    {
        parent::boot();

        self::deleting(function (Link $model) {
            $model->visitors()->delete();
        });
    }
}
