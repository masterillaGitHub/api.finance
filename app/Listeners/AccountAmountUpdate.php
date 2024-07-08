<?php

namespace App\Listeners;

use App\Enums\TransactionType;
use App\Events\TransactionCreated;
use App\Models\Account;
use App\Models\AccountSum;
use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AccountAmountUpdate
{
    private TransactionType $type;

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
    public function handle(TransactionCreated $event): void
    {
        $this->init($event->transaction);

        $this->updateAccount(
            $event->transaction->account_id,
            $event->transaction->currency_id,
            $event->transaction->amount,
        );

        if ($this->type->isTransfer()) {
            $this->updateAccount(
                $event->transaction->to_account_id,
                $event->transaction->to_currency_id,
                $event->transaction->to_amount,
            );
        }
    }

    private function updateAccount(int $accountId, int $currencyId, int $amount): void
    {
        $accountSum = $this->loadAccountSum($accountId, $currencyId);

        if (is_null($accountSum)) {
            $accountSum = $this->getNewAccountSum($accountId, $currencyId);
        }

        $accountSum->balance = $accountSum->balance + $amount;
        $accountSum->save();
    }

    private function init(Transaction $transaction): void
    {
        $this->type = TransactionType::from($transaction->type_id);
    }

    private function loadAccountSum(int $accountId, int $currencyId): ?AccountSum
    {
        return AccountSum::query()
            ->where('account_id', $accountId)
            ->where('currency_id', $currencyId)
            ->first();
    }

    private function getNewAccountSum(int $accountId, int $currencyId): AccountSum
    {
        $sum = new AccountSum();
        $sum->account_id = $accountId;
        $sum->currency_id = $currencyId;
        $sum->balance = 0;

        return $sum;
    }
}
