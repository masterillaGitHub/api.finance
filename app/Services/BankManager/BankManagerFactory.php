<?php
declare(strict_types=1);

namespace App\Services\BankManager;

use App\Models\BankConnection;

final class BankManagerFactory
{
    /**
     * @throws \Exception
     */
    public static function make(int $bankId): AbstractBankManager
    {
        $connection = BankConnection::find($bankId);

        return match ($bankId) {
            1 => new MonoBankManager($connection),
            default => throw new \Exception("Bank Manager Error by bank id $bankId"),
        };
    }
}
