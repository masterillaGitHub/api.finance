<?php

namespace App\Models;

use App\Enums\AccountPlaceTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = false;

    public function getPlaceType(): AccountPlaceTypeEnum
    {
        return AccountPlaceTypeEnum::from($this->place_type);
    }

    // Relations
    public function category(): BelongsTo
    {
        return $this->belongsTo(AccountCategory::class,  'category_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function sums(): HasMany
    {
        return $this->hasMany(AccountSum::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function lastTransaction(): HasOne
    {
        return $this->hasOne(Transaction::class)
            ->latest('transaction_at');
    }
}
