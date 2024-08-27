<?php

namespace App\Listeners;

use App\Actions\UpdateBalanceAccountSum;
use App\Enums\TransactionType;
use App\Events\Transaction\TransactionUpdated;
use App\Models\Transaction;

class AccountSumToUpdateAmount
{
    private TransactionType $type;
    private UpdateBalanceAccountSum $accountSum;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->accountSum = new UpdateBalanceAccountSum();
    }

    /**
     * Handle the event.
     */
    public function handle(TransactionUpdated $event): void
    {
        $t = $event->transaction;
        $type = $this->getTransactionType($t);

        if ($t->isDirty(['amount', 'to_amount'])) {
            $differenceOfAmount = $t->getAttribute('amount') - $t->getOriginal('amount');

            $this->accountSum->toAdd($t->account_id, $t->currency_id, $differenceOfAmount);

            if ($type->isTransfer()) {
                $differenceOfToAmount = $t->getAttribute('to_amount') - $t->getOriginal('to_amount');

                $this->accountSum->toAdd($t->to_account_id, $t->to_currency_id, $differenceOfToAmount);
            }
        }
    }

    private function getTransactionType(Transaction $t): TransactionType
    {
        return TransactionType::from($t->type_id);
    }
}
