<?php
declare(strict_types=1);

namespace App\Services\BankManager;

use Illuminate\Support\Collection;

final class BankManagerService
{
    /**
     * @throws \Exception
     */
    public function getAccountsByBankId(int $bankId): Collection
    {
        $bankManager = BankManagerFactory::make($bankId);

        return $bankManager->getAccounts();
    }
}
