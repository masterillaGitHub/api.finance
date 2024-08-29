<?php

namespace Database\Factories;

use App\Enums\Currency;
use App\Enums\TransactionType;
use App\Models\Account;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws \Exception
     */
    public function definition(): array
    {
        $user = $this->getUser();
        $amount = $this->getAmount();
        $typeId = $this->getTypeId($amount);

        return [
            'user_id' => $user->id,
            'type_id' => $typeId,
            'category_id' => $this->getCategory($user, $typeId),
            'account_id' => $this->getAccount($user),
            'currency_id' => $this->getCurrencyId(),
            'amount' => $this->getAmount(),
            'description' => $this->faker->boolean(20) ? $this->faker->sentence() : null,
            'note' => $this->faker->boolean(50) ? $this->faker->sentence() : null,
            'accrual_at' => $this->faker->boolean(10)
                ? $this->faker->dateTimeBetween('-1 months')
                : null,
            'transaction_at' => $this->faker->dateTimeBetween('-1 years'),
        ];
    }

    private function getUser(): User
    {
        return User::query()->inRandomOrder()->first();
    }

    private function getTypeId(int $amount): int
    {
        return $amount > 0
            ? TransactionType::INCOME->value
            : TransactionType::EXPENSE->value;
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

    private function getAccount(User $user): Account
    {
        return Account::query()
            ->where('user_id', $user->id)
            ->inRandomOrder()
            ->first();
    }

    private function getCategory(User $user, int $typeId): ?TransactionCategory
    {
        return TransactionCategory::query()
            ->where('user_id', $user->id)
            ->where('type_id', $typeId)
            ->inRandomOrder()
            ->first();
    }

    private function getCurrencyId(): int
    {
        return $this->faker->boolean(80)
            ? Currency::UAH->value
            : $this->faker->randomElement([
                Currency::USD->value,
                Currency::EUR->value
            ]);
    }
}
