<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property      int         $id
 * @property      int         $user_id
 * @property      string|null $ip_address
 * @property      string|null $browser
 * @property      string|null $os
 * @property      Carbon|null $tarih
 * @property      Carbon|null $created_at
 * @property      Carbon|null $updated_at
 * @property      Carbon|null $deleted_at
 * @property-read User|null   $user
 */
class Login extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'ip_address', 'browser', 'os', 'tarih'];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'tarih'   => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function getDate(): ?string
    {
        return ($this->tarih ?? $this->created_at)?->locale('tr_TR')->isoFormat('lll');
    }
}
