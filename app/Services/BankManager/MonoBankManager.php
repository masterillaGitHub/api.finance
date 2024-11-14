<?php
declare(strict_types=1);

namespace App\Services\BankManager;

use App\Data\MonoBankAccountData;
use App\Data\MonoBankStatementData;
use App\Integrations\MonobankApi;
use App\Models\Currency;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
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

    /**
     * @throws GuzzleException
     */
    public function getStatement(int|string $accountId, Carbon $dateFrom, Carbon $dateTo = null): Collection
    {
        $mono = new MonobankApi($this->connection->token);
        try {
            $res = $mono->getStatement($accountId, $dateFrom, $dateTo);
        }
        catch (\GuzzleHttp\Exception\RequestException $e) {
            logs()->error('GuzzleHttp RequestException', [
                'message' => json_decode($e->getResponse()->getBody()->getContents(), true)
            ]);
            // TODO: Some testing and remove after 10.10.2025
            dd('GuzzleHttp RequestException', [
                'content' => json_decode($e->getResponse()->getBody()->getContents(), true),
                'message' => $e->getMessage(),
            ]);
        }

        return collect($res)
            ->map(fn(array $statement): MonoBankStatementData => new MonoBankStatementData(
                $statement['id'],
                $statement['time'],
                $statement['description'],
                $statement['mcc'],
                $statement['originalMcc'],
                $statement['amount'],
                $statement['hold'],
                $statement['operationAmount'],
                $statement['currencyCode'],
                $statement['commissionRate'],
                $statement['cashbackAmount'],
                $statement['balance'],
                $statement['receiptId'] ?? null,
                $statement['comment'] ?? null,
                $statement['invoiceId'] ?? null,
                $statement['counterEdrpou'] ?? null,
                $statement['counterIban'] ?? null,
                $statement['counterName'] ?? null,
            ));
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
