<?php

namespace App\Observers;

use App\Enums\TransactionInputType;
use App\Models\Transaction;
use App\Services\Observers\AccountSum\AccountSumToAddAmount;
use App\Services\Observers\AccountSum\AccountSumToRemoveAmount;
use App\Services\Observers\AccountSum\AccountSumToUpdateAmount;
use App\Services\Observers\Transaction\TransactionTransfer;

class TransactionObserver
{
    /**
     * Handle the AccountSum "created" event.
     */
    public function created(Transaction $transaction): void
    {
        if ($transaction->getInputType() === TransactionInputType::MANUAL) {
            (new AccountSumToAddAmount())->handle($transaction);
        }
    }

    public function updating(Transaction $transaction): void
    {
    }

    public function updated(Transaction $transaction): void
    {
        $transferService = new TransactionTransfer($transaction);

        if ($transferService->isSwitchedToNotTransfer()) {
            $transferService->removeByUpdate();
        }
        else if ($transferService->isSwitchedToTransfer()) {
            $transferService->restore();
        }

        if ($transaction->getInputType() === TransactionInputType::MANUAL) {
            (new AccountSumToUpdateAmount())->handle($transaction);
        }
    }

    /**
     * Handle the AccountSum "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        $transferService = new TransactionTransfer($transaction);

        if ($transferService->isTransfer() && $transferService->hasTransfer()) {
            $transferService->remove();
        }

        if ($transaction->getInputType() === TransactionInputType::MANUAL) {
            (new AccountSumToRemoveAmount())->handle($transaction);
        }
    }

    /**
     * Handle the AccountSum "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        if ($transaction->getInputType() === TransactionInputType::MANUAL) {
            (new AccountSumToAddAmount())->handle($transaction);
        }
    }

    /**
     * Handle the AccountSum "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
