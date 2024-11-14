<?php
declare(strict_types=1);

namespace App\Services\BankAccount;

use App\Models\Account;
use App\Enums\AccountCategoryIdEnum;
use App\Enums\AccountPlaceTypeEnum;
use App\Enums\TransactionInputTypeEnum;
use App\Models\Currency;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

final class BankAccountStoreAccounts
{
    private Collection $currencies;

    public function __construct()
    {
        $this->currencies = Currency::all();
    }

    public function handle(array $data): void
    {
        collect($data['accounts'])
            ->map(fn(array $account): array => $this->prepareDataAccount($data['bankId'], $account))
            ->map(fn(array $account): Account => $this->storeAccount($account));
    }


    /**
     * @throws Throwable
     */
    private function storeAccount(array $data): Account
    {
        try {
            DB::beginTransaction();

            $account = Account::updateOrCreate(
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
            'category_id' => AccountCategoryIdEnum::BANK_ACCOUNT,
            'currency_id' => $this->findCurrencyByNumericCode($account['currencyCode'])->id,
            'input_type' => TransactionInputTypeEnum::AUTO,
            'place_type' => AccountPlaceTypeEnum::INTERNAL,
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
