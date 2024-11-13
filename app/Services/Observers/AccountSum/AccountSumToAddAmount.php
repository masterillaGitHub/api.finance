<?php

namespace App\Services\Observers\AccountSum;

use App\Models\Transaction;

class AccountSumToAddAmount extends AccountSumMain
{

    /**
     * Handle the event.
     */
    public function handle(Transaction $t): void
    {
        $this->init($t);

        if ($this->isAccountInternal($t->account_id)) {
            $this->accountSum->toAdd(
                $t->account_id,
                $t->currency_id,
                $t->amount,
            );
        }
    }
}
