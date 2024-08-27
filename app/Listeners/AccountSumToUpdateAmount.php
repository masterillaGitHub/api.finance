<?php

namespace App\Listeners;

use App\Actions\UpdateBalanceAccountSum;
use App\Enums\TransactionType;
use App\Events\Transaction\TransactionUpdated;
use App\Models\Transaction;

class AccountSumToUpdateAmount
{
    private UpdateBalanceAccountSum $accountSum;
    private Transaction $t;

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
        $this->t = $event->transaction;
        $type = $this->getTransactionType($event->transaction);

        $this->updateAccountSum(
            'account_id',
            'currency_id',
            'amount'
        );

        if ($type->isTransfer()) {
            $this->updateAccountSum(
                'to_account_id',
                'to_currency_id',
                'to_amount'
            );
        }
    }

    private function updateAccountSum(
        string $accountFiledName,
        string $currencyFiledName,
        string $amountFiledName
    ): void
    {
        if ($this->t->isClean([$accountFiledName, $currencyFiledName, $amountFiledName])) {
            return;
        }

        $originAccountId = $this->t->getOriginal($accountFiledName);
        $newAccountId = $this->t->getAttribute($accountFiledName);

        $originCurrencyId = $this->t->getOriginal($currencyFiledName);
        $newCurrencyId = $this->t->getAttribute($currencyFiledName);

        if ($this->isAccountEqual($accountFiledName) && $this->isCurrencyEqual($currencyFiledName)) {
            $amount = $this->getDifferenceAmount($amountFiledName);
        }
        else {
            $amount = $this->t->getAttribute($amountFiledName);

            $this->accountSum->toRemove($originAccountId, $originCurrencyId, $amount);
        }

        $this->accountSum->toAdd($newAccountId, $newCurrencyId, $amount);
    }

    private function isAccountEqual(string $filedName): bool
    {
        return $this->t->getOriginal($filedName) === $this->t->getAttribute($filedName);
    }

    private function isCurrencyEqual(string $filedName): bool
    {
        return $this->t->getOriginal($filedName) === $this->t->getAttribute($filedName);
    }

    private function getDifferenceAmount(string $filedName): int
    {
        return $this->t->getAttribute($filedName) - $this->t->getOriginal($filedName);
    }

    private function getTransactionType(Transaction $t): TransactionType
    {
        return TransactionType::from($t->type_id);
    }
}
