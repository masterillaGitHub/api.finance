<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountSum;
use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class AccountSumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = Currency::all();

        Account::all()->each(
            fn (Account $account) => $this->createAccountSums($account, $this->randomCurrencies($currencies))
        );
    }

    private function createAccountSums(Account $account, Collection $currencies): void
    {
        $currencies->each(
            fn (Currency $currency) => $this->createAccountSum($account, $currency)
        );
    }

    private function createAccountSum(Account $account, Currency $currency): void
    {
        AccountSum::factory()->create([
            'currency_id' => $currency->id,
            'account_id' => $account->id,
        ]);
    }

    private function randomCurrencies(Collection $currencies): Collection
    {
        return $currencies->random(mt_rand(1, $currencies->count()));
    }
}
