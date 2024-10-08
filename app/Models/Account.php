<?php

namespace App\Models;

use App\Enums\AccountPlaceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = false;

    public function getPlaceType(): AccountPlaceType
    {
        return AccountPlaceType::from($this->place_type);
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
}
