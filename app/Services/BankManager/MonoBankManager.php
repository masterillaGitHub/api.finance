<?php
declare(strict_types=1);

namespace App\Services\BankManager;

use App\Data\MonoBankAccountData;
use App\Integrations\MonobankApi;
use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class MonoBankManager extends AbstractBankManager
{
    private Collection $currencies;

    public function getAccounts(): Collection
    {
        $mono = new MonobankApi($this->connection->token);
        $this->currencies = Currency::all();

        return collect($mono->getAccounts())
            ->map(fn(array $account) => $this->prepareAccountData($account));
    }

    public function getStatement(int $accountId, Carbon $dateFrom, Carbon $dateTo = null): Collection
    {
        return collect();
    }

    private function prepareAccountData(array $account): MonoBankAccountData
    {
        return MonoBankAccountData::from([
            'id' => $account['id'],
            'maskedPan' => $account['maskedPan'][0] ?? null,
            'currency' => $this->currencies->firstWhere('numeric_code', $account['currencyCode'])->alphabetic_code,
            'currencyCode' => $account['currencyCode'],
            'balance' => $account['balance'],
            'creditLimit' => $account['creditLimit'],
            'type' => $account['type'],
        ]);
    }
}
