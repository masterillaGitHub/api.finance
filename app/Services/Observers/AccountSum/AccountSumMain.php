<?php
declare(strict_types=1);

namespace App\Services\Observers\AccountSum;

use App\Actions\UpdateBalanceAccountSum;
use App\Enums\AccountPlaceTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Account;
use App\Models\Transaction;

class AccountSumMain
{
    protected UpdateBalanceAccountSum $accountSum;
    protected TransactionTypeEnum $type;

    public function __construct()
    {
        $this->accountSum = new UpdateBalanceAccountSum();
    }

    protected function isAccountInternal(int $accountId): bool
    {
        return Account::query()
            ->where('id', $accountId)
            ->where('place_type', AccountPlaceTypeEnum::INTERNAL)
            ->exists();
    }

    protected function init(Transaction $transaction): void
    {
        $this->type = TransactionTypeEnum::from($transaction->type_id);
    }
}
