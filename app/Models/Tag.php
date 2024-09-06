<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Tags\Tag as BaseTag;

class Tag extends BaseTag implements Sortable
{
    use SortableTrait;

    // Sorting
    public function buildSortQuery(): Builder
    {
        return static::query()->where('user_id', $this->user_id);
    }

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
