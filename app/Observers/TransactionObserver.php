<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Services\Observers\AccountSum\AccountSumToAddAmount;
use App\Services\Observers\AccountSum\AccountSumToRemoveAmount;
use App\Services\Observers\AccountSum\AccountSumToUpdateAmount;

class TransactionObserver
{
    /**
     * Handle the AccountSum "created" event.
     */
    public function created(Transaction $transaction): void
    {
        (new AccountSumToAddAmount())->handle($transaction);
    }

    public function updating(Transaction $transaction): void
    {
    }

    public function updated(Transaction $transaction): void
    {
        (new AccountSumToUpdateAmount())->handle($transaction);
    }

    /**
     * Handle the AccountSum "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        (new AccountSumToRemoveAmount())->handle($transaction);
    }

    /**
     * Handle the AccountSum "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the AccountSum "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
