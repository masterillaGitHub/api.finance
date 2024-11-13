<?php

namespace App\Models;

use App\Enums\TransactionInputType;
use App\Observers\TransactionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\TransactionType as TransactionTypeEnum;


#[ObservedBy([TransactionObserver::class])]
class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = false;

    protected $casts = [
        'accrual_at' => 'datetime',
        'created_at' => 'datetime',
        'transaction_at' => 'datetime',
    ];

    public function getType(): TransactionTypeEnum
    {
        return TransactionTypeEnum::from($this->type_id);
    }

    public function getInputType(): TransactionInputType
    {
        return TransactionInputType::from($this->input_type);
    }

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class, 'type_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'category_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function transfer_transaction(): HasOne
    {
        return $this->hasOne(Transaction::class, 'transfer_transaction_id', 'id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(TransactionTag::class, 'transaction_transaction_tag');
    }
}
