<?php

namespace App\Services;

use App\Services\BankAccount\BankAccountStoreAccounts;
use App\Services\BankAccount\BankAccountUpdateTransactions;

class BankAccountService
{

    public function storeAccounts(array $data): void
    {
        (new BankAccountStoreAccounts())->handle($data);
    }

    public function updateTransactions(array $data): void
    {
        (new BankAccountUpdateTransactions())->handle($data);
    }

}
