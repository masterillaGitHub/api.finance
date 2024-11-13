<?php

namespace App\Services\Observers\AccountSum;

use App\Models\Transaction;

class AccountSumToUpdateAmount extends AccountSumMain
{
    private Transaction $t;

    /**
     * Handle the event.
     */
    public function handle(Transaction $t): void
    {
        $this->t = $t;
        $this->init($t);

        $this->updateAccountSum(
            'account_id',
            'currency_id',
            'amount',
        );
    }

    private function updateAccountSum(
        string $accountFieldName,
        string $currencyFieldName,
        string $amountFieldName,
    ): void
    {
        if ($this->t->isClean([$accountFieldName, $currencyFieldName, $amountFieldName])) {
            return;
        }

        $originAccountId = $this->t->getOriginal($accountFieldName);
        $newAccountId = $this->t->getAttribute($accountFieldName);

        $originCurrencyId = $this->t->getOriginal($currencyFieldName);
        $newCurrencyId = $this->t->getAttribute($currencyFieldName);

        $amount = $this->t->getAttribute($amountFieldName);

        if ($this->isNeedToChangeAmount($accountFieldName, $currencyFieldName, $amountFieldName)) {
            $amount = $this->getDifferenceAmount($amountFieldName);
        }

        // If 'account' or 'amount' was changed
        if ($this->t->isDirty([$accountFieldName, $currencyFieldName])) {
            if ($this->isAccountInternal($originAccountId)) {
                $this->accountSum->toRemove($originAccountId, $originCurrencyId, $amount);
            }
        }

        if ($this->isAccountInternal($newAccountId)) {
            $this->accountSum->toAdd($newAccountId, $newCurrencyId, $amount);
        }
    }

    private function getDifferenceAmount(string $filedName): int
    {
        return $this->t->getAttribute($filedName) - $this->t->getOriginal($filedName);
    }

    private function isNeedToChangeAmount(
        string $accountFieldName,
        string $currencyFieldName,
        string $amountFieldName
    ): bool
    {
        // If only 'amount' was changed, and 'account', 'currency' was not changed
        return $this->t->isDirty($amountFieldName)
            && $this->isNotChangedAccountAndCurrency($accountFieldName, $currencyFieldName);
    }

    private function isNotChangedAccountAndCurrency(
        string $accountFieldName,
        string $currencyFieldName
    ): bool
    {
        return $this->t->isClean([$accountFieldName, $currencyFieldName]);
    }
}
