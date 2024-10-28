<?php
declare(strict_types=1);

namespace App\Services\BankManager;

use App\Models\BankConnection;
use Carbon\Carbon;
use Illuminate\Support\Collection;

abstract class AbstractBankManager
{
    public function __construct(
        protected readonly BankConnection $connection,
    )
    {

    }

    abstract public function getAccounts(): Collection;
    abstract public function getStatement(int $accountId, Carbon $dateFrom, Carbon $dateTo = null): Collection;
}
