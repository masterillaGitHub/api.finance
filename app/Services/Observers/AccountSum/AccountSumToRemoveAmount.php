<?php

namespace App\Services\Observers\AccountSum;

use App\Models\Transaction;

class AccountSumToRemoveAmount extends AccountSumMain
{

    /**
     * Handle the event.
     */
    public function handle(Transaction $t): void
    {
        $this->init($t);

        if ($this->isAccountInternal($t->account_id)) {
            $this->accountSum->toRemove(
                $t->account_id,
                $t->currency_id,
                $t->amount,
            );
        }
    }
}
