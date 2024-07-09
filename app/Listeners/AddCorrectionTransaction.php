<?php

namespace App\Listeners;

use App\Enums\TransactionParentCategory;
use App\Enums\TransactionType;
use App\Events\AccountSum\AccountSumUpdated;
use App\Models\Transaction;

class AddCorrectionTransaction
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
    public function handle(AccountSumUpdated $event): void
    {
        $item = $event->accountSum;

        if ($item->isDirty('balance')) {
            $differenceOfAmount = $item->getAttribute('balance') - $item->getOriginal('balance');
            $typeId = $differenceOfAmount > 0
                ? TransactionType::INCOME->value
                : TransactionType::EXPENSE->value;

            Transaction::create([
                'user_id' => auth()->id(),
                'type_id' => $typeId,
                'account_id' => $item->getAttribute('account_id'),
                'category_id' => TransactionParentCategory::CORRECTION,
                'currency_id' => $item->getAttribute('currency_id'),
                'amount' => $differenceOfAmount,
                'transaction_at' => now(),
            ]);
        }
    }
}
