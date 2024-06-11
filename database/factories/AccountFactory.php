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

    private function arrayRandom(array $array): string
    {
        return $array[array_rand($array)];
    }
}
