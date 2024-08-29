<?php

namespace Database\Factories;

use App\Enums\TransactionType;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionCategory>
 */
class TransactionCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws \Exception
     */
    public function definition(): array
    {
        $typeId = $this->faker->boolean(85)
            ? TransactionType::EXPENSE->value
            : TransactionType::INCOME->value;
        $name = $this->faker->word() . ' ' . ($typeId === 1 ? 'витрата' : 'дохід');

        return [
            'user_id' => User::query()->inRandomOrder()->first(),
            'parent_id' => null,
            'type_id' => $typeId,
            'name' => $name,
            'icon' => 'mdi-format-list-bulleted-type'
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (TransactionCategory $category) {
            if ($this->faker->boolean(75)) {
                $this->createChild($category);
            }
        });
    }

    private function createChild(TransactionCategory $category): void
    {
        $parent = TransactionCategory::inRandomOrder()->first();

        if ($parent) {
            $category->parent_id = $parent->id;
            $category->save();
        }
    }
}
