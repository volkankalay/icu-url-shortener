<?php

namespace App\Models;

use App\Casts\VisitorOptionsCast;
use App\Support\VisitorOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property      int                 $id
 * @property      int                 $link_id
 * @property      VisitorOptions|null $options
 * @property      Carbon|null         $created_at
 * @property      Carbon|null         $updated_at
 * @property      Carbon|null         $deleted_at
 * @property-read Link|null           $link
 */
class Visitor extends Model
{
    use SoftDeletes;

    protected $fillable = ['link_id', 'options'];

    protected function casts(): array
    {
        return [
            'link_id' => 'integer',
            'options' => VisitorOptionsCast::class,
        ];
    }

    /** @return BelongsTo<Link, $this> */
    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class)->withTrashed();
    }

    public function getDate(): ?string
    {
        return $this->created_at?->locale('tr_TR')->isoFormat('lll');
    }
}
