<?php
declare(strict_types=1);

namespace App\Actions;

use App\Models\AccountSum;

final class UpdateBalanceAccountSum
{

    public function toAdd(int $accountId, int $currencyId, int $amount): void
    {
        $accountSum = $this->getAccountSum($accountId, $currencyId);

        $accountSum->balance = $accountSum->balance + $amount;
        $accountSum->save();
    }

    public function toRemove(int $accountId, int $currencyId, int $amount): void
    {
        $accountSum = $this->getAccountSum($accountId, $currencyId);

        $accountSum->balance = $accountSum->balance - $amount;
        $accountSum->save();
    }

    private function getAccountSum(int $accountId, int $currencyId): AccountSum
    {
        $accountSum = $this->loadAccountSum($accountId, $currencyId);

        if (is_null($accountSum)) {
            $accountSum = $this->getNewAccountSum($accountId, $currencyId);
        }

        return $accountSum;
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
