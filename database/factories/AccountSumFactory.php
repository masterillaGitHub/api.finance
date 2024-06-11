<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountSumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::inRandomOrder()->first(),
            'currency_id' => Currency::inRandomOrder()->first(),
            'balance' => mt_rand(0, 100000),
        ];
    }
}
