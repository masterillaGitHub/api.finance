<?php
declare(strict_types=1);

namespace App\Services\Observers\AccountSum;

use App\Actions\UpdateBalanceAccountSum;
use App\Enums\AccountPlaceType;
use App\Enums\TransactionType;
use App\Models\Account;
use App\Models\Transaction;

class AccountSumMain
{
    protected UpdateBalanceAccountSum $accountSum;
    protected TransactionType $type;

    public function __construct()
    {
        $this->accountSum = new UpdateBalanceAccountSum();
    }

    protected function isAccountInternal(int $accountId): bool
    {
        return Account::query()
            ->where('id', $accountId)
            ->where('place_type', AccountPlaceType::INTERNAL)
            ->exists();
    }

    protected function init(Transaction $transaction): void
    {
        $this->type = TransactionType::from($transaction->type_id);
    }
}
