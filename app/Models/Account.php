<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = false;

    // Relations
    public function category(): BelongsTo
    {
        return $this->belongsTo(AccountCategory::class, 'account_category_id' );
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
