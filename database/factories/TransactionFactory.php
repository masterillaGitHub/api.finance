<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    const int TYPE_EXPENSE = 1;
    const int TYPE_INCOME = 2;
    const int TYPE_TRANSFER = 3;
    const int CURRENCY_UAH = 1;
    const int CURRENCY_USD = 2;
    const int CURRENCY_EUR = 3;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws \Exception
     */
    public function definition(): array
    {
        $user = User::query()->inRandomOrder()->first();
        $typeId = $this->faker->randomElement([self::TYPE_EXPENSE, self::TYPE_INCOME]);
        $category = TransactionCategory::query()
            ->where('user_id', $user->id)
            ->where('type_id', $typeId)
            ->inRandomOrder()
            ->first();
        $account = Account::query()
            ->where('user_id', $user->id)
            ->inRandomOrder()
            ->first();
        $currencyId = $this->faker->boolean(80)
            ? self::CURRENCY_UAH
            : $this->faker->randomElement([self::CURRENCY_USD, self::CURRENCY_EUR]);

        return [
            'user_id' => User::query()->inRandomOrder()->first(),
            'type_id' => $typeId,
            'category_id' => $category,
            'account_id' => $account,
            'currency_id' => $currencyId,
            'transfer_transaction_id' => null,
            'amount' => $this->getAmount(),
            'note' => $this->faker->boolean(20) ? $this->faker->sentence() : null,
            'transaction_at' => $this->faker->boolean(10)
                ? $this->faker->dateTimeBetween('-1 months')
                : null,
            'created_at' => $this->faker->dateTimeBetween('-1 years'),
        ];
    }

    private function getAmount(): int
    {
        $amount = $this->faker->boolean(90)
            ? mt_rand(-1000,-1)
            : mt_rand(-10000000000,-1000);

        if ($this->faker->boolean(30)) {
            $amount *= -1;
        }

        return $amount;
    }
}
