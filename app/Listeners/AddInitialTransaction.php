<?php

namespace App\Listeners;

use App\Enums\TransactionParentCategory;
use App\Enums\TransactionType;
use App\Events\AccountSum\AccountSumCreated;
use App\Models\Transaction;

class AddInitialTransaction
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AccountSumCreated $event): void
    {
        $item = $event->accountSum;
        $data = $event->data;

        $typeId = $data['balance'] > 0
            ? TransactionType::INCOME->value
            : TransactionType::EXPENSE->value;

        $t = new Transaction();
        $t->fill([
            'user_id' => auth()->id(),
            'type_id' => $typeId,
            'account_id' => $item->getAttribute('account_id'),
            'category_id' => TransactionParentCategory::INITIAL,
            'currency_id' => $item->getAttribute('currency_id'),
            'amount' => $data['balance'],
            'transaction_at' => now(),
        ])
        ->saveQuietly();
    }
}
