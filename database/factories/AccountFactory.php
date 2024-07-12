<?php

namespace Database\Factories;

use App\Enums\TransactionInputType;
use App\Models\AccountCategory;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws \Exception
     */
    public function definition(): array
    {
        $bankName = getFate(20) ? $this->randomBankName() : null;

        return [
            'user_id' => User::inRandomOrder()->first(),
            'category_id' => AccountCategory::inRandomOrder()->first(),
            'currency_id' => Currency::inRandomOrder()->first(),
            'input_type' => TransactionInputType::MANUAL,
            'name' => $this->randomName(),
            'icon' => $this->randomIcon(),
            'bank_name' => $bankName,
            'credit_limit' => $bankName ? mt_rand(100, 20000) : null,
        ];
    }

    private function randomName(): string
    {
        $names = [
            'Готівка',
            'Основний рахунок',
            'Кредитна картка #1',
            'Кредитна картка #2',
            'Електронний гаманець',
            'Рахунок PayPal',
            'Рахунок Revolut',
            'Рахунок TransferWise',
            'Зарплатний рахунок',
            'Фінансовий рахунок (інвестиції)',
            'Рахунок в іншому банку',
            'Рахунок за кредитом',
            'Заощадження на відпустку',
        ];

        return $this->arrayRandom($names);
    }

    private function randomBankName(): string
    {
        $names = [
            'ПриватБанк',
            'Ощадбанк',
            'Укрсоцбанк',
            'Райффайзен Банк Аваль',
            'Альфа-Банк',
            'Кредобанк',
            'Укргазбанк',
            'ПУМБ',
            'Кредит Дніпро',
            'Мегабанк',
            'Фінанси та Кредит',
            'Південний',
            'ПРОКРЕДИТ БАНК',
            'Кредитвест Банк',
            'Восток',
            'Перший інвестиційний банк',
            'Капітал Банк',
            'Правекс-Банк',
            'Український Кредит',
            'Таскомбанк',
        ];

        return $this->arrayRandom($names);
    }

    private function randomIcon(): string
    {
        $names = [
            'mdi-account-cash', 'mdi-account-cash-outline', 'mdi-account-credit-card', 'mdi-account-credit-card-outline', 'mdi-bank', 'mdi-bank-check', 'mdi-bank-circle', 'mdi-bank-circle-outline', 'mdi-bank-minus', 'mdi-bank-off', 'mdi-bank-off-outline', 'mdi-bank-outline', 'mdi-bank-plus', 'mdi-bank-remove', 'mdi-bank-transfer', 'mdi-bank-transfer-in', 'mdi-bank-transfer-out', 'mdi-bitcoin', 'mdi-cash', 'mdi-cash-100', 'mdi-cash-check', 'mdi-cash-clock', 'mdi-cash-edit', 'mdi-cash-fast', 'mdi-cash-lock', 'mdi-cash-lock-open', 'mdi-cash-marker', 'mdi-cash-minus', 'mdi-cash-multiple', 'mdi-cash-off', 'mdi-cash-plus', 'mdi-cash-refund', 'mdi-cash-register', 'mdi-cash-remove', 'mdi-cash-sync', 'mdi-checkbook', 'mdi-checkbook-arrow-left', 'mdi-checkbook-arrow-right', 'mdi-circle-multiple', 'mdi-circle-multiple-outline', 'mdi-credit-card', 'mdi-credit-card-check', 'mdi-credit-card-check-outline', 'mdi-credit-card-chip', 'mdi-credit-card-chip-outline', 'mdi-credit-card-clock', 'mdi-credit-card-clock-outline', 'mdi-credit-card-edit'
        ];

        return $this->arrayRandom($names);
    }

    private function arrayRandom(array $array): string
    {
        return $array[array_rand($array)];
    }
}
