<?php
declare(strict_types=1);

namespace App\Actions;

use App\Models\AccountSum;

final class UpdateBalanceAccountSum
{

    public function update(int $accountId, int $currencyId, int $amount): void
    {
        $accountSum = $this->loadAccountSum($accountId, $currencyId);

        if (is_null($accountSum)) {
            $accountSum = $this->getNewAccountSum($accountId, $currencyId);
        }

        $accountSum->balance = $accountSum->balance + $amount;
        $accountSum->save();
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
