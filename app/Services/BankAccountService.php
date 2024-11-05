<?php

namespace App\Services;

use App\Enums\AccountCategoryId;
use App\Enums\AccountPlaceType;
use App\Enums\TransactionInputType;
use App\Models\Account as Model;
use App\Models\Currency;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class BankAccountService
{
    private Collection $currencies;

    public function __construct()
    {
        $this->currencies = Currency::all();
    }

    public function storeAccounts(array $data): void
    {
        collect($data['accounts'])
            ->map(fn(array $account): array => $this->prepareDataAccount($data['bankId'], $account))
            ->map(fn(array $account): Model => $this->storeAccount($account));
    }

    /**
     * @throws Throwable
     */
    private function storeAccount(array $data): Model
    {
        try {
            DB::beginTransaction();

            $account = Model::createOrFirst(
                ['id_origin' => $data['id_origin']],
                $data
            );

            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }

        return $account;
    }

    private function prepareDataAccount(int $bankId, array $account): array
    {
        return [
            'user_id' => auth()->id(),
            'category_id' => AccountCategoryId::BANK_ACCOUNT,
            'currency_id' => $this->findCurrencyByNumericCode($account['currencyCode'])->id,
            'input_type' => TransactionInputType::AUTO,
            'place_type' => AccountPlaceType::INTERNAL,
            'name' => $account['name'],
            'icon' => 'mdi-bank',
            'credit_limit' => $account['creditLimit'] ?? null,
            'bank_id' => $bankId,
            'id_origin' => $account['idOrigin'],
        ];
    }

    private function findCurrencyByNumericCode(int $numericCode): ?Currency
    {
        return $this->currencies->first(
            fn(Currency $currency) => $currency->numeric_code === $numericCode
        );
    }

}
