<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property string      $site
 * @property string|null $title
 * @property string|null $keywords
 * @property string|null $description
 * @property string|null $logo
 * @property string|null $favicon
 * @property string|null $header_code
 * @property string|null $footer_code
 * @property bool        $login
 * @property bool        $register
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class Setting extends Model
{
    use SoftDeletes;

    private const DEFAULT_LOGO = 'img/icu-shortener.svg';

    protected $fillable = [
        'site', 'title', 'keywords', 'description', 'logo', 'login', 'register',
        'favicon', 'header_code', 'footer_code',
    ];

    protected function casts(): array
    {
        return [
            'login'    => 'boolean',
            'register' => 'boolean',
        ];
    }

    public function logoUrl(): string
    {
        return $this->brandImageUrl($this->logo);
    }

    public function faviconUrl(): string
    {
        return $this->brandImageUrl($this->favicon);
    }

    private function brandImageUrl(?string $url): string
    {
        return blank($url) ? asset(self::DEFAULT_LOGO) : $url;
    }
}
