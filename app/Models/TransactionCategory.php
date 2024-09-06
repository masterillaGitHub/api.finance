<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class TransactionCategory extends Model implements Sortable
{
    use HasFactory, SoftDeletes, SortableTrait;

    protected $guarded = false;

    // Sorting
    public function buildSortQuery(): Builder|TransactionCategory
    {
        return static::query()
            ->where('type_id', $this->type_id)
            ->where('parent_id', $this->parent_id)
            ->where('user_id', $this->user_id);
    }

    // Relations
    public function parent(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(TransactionCategory::class,'parent_id', 'id' );
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'category_id');
    }
}
