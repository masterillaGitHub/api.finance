<?php

namespace App\Listeners;

use App\Actions\UpdateBalanceAccountSum;
use App\Enums\TransactionType;
use App\Events\TransactionDestroyed;
use App\Models\Transaction;

class AccountSumToRemoveAmount
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
    public function handle(TransactionDestroyed $event): void
    {
        $this->init($event->transaction);

        $this->accountSum->toRemove(
            $event->transaction->account_id,
            $event->transaction->currency_id,
            $event->transaction->amount,
        );

        if ($this->type->isTransfer()) {
            $this->accountSum->toRemove(
                $event->transaction->to_account_id,
                $event->transaction->to_currency_id,
                $event->transaction->to_amount,
            );
        }
    }

    private function init(Transaction $transaction): void
    {
        $this->type = TransactionType::from($transaction->type_id);
    }
}
