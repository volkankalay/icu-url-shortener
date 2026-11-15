<?php

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;

/**
 * @property      int                    $id
 * @property      string                 $name
 * @property      string                 $surname
 * @property      string                 $email
 * @property      string                 $username
 * @property      string                 $password
 * @property      UserType               $user_type
 * @property      string|null            $remember_token
 * @property      Carbon|null            $created_at
 * @property      Carbon|null            $updated_at
 * @property      Carbon|null            $deleted_at
 * @property-read string                 $full_name
 * @property-read Collection<int, Link>  $links
 * @property-read Collection<int, Login> $logins
 */
class User extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'surname', 'email', 'username', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $attributes = ['user_type' => UserType::REGULAR->value];

    protected function casts(): array
    {
        return [
            'user_type' => UserType::class,
            'password'  => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->user_type === UserType::ADMIN;
    }

    /** @return Attribute<string, never> */
    protected function fullName(): Attribute
    {
        return Attribute::get(fn (): string => trim($this->name . ' ' . $this->surname));
    }

    /** @return HasMany<Link, $this> */
    public function links(): HasMany
    {
        return $this->hasMany(Link::class, 'owner_id');
    }

    /** @return HasMany<Login, $this> */
    public function logins(): HasMany
    {
        return $this->hasMany(Login::class);
    }

    public function linkCount(): int
    {
        return $this->links()->count();
    }

    public function getCreatedDate(): ?string
    {
        return $this->created_at?->locale('tr_TR')->isoFormat('lll');
    }
}
