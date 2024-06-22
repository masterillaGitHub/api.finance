<?php
declare(strict_types=1);

namespace App\Actions;

use App\Models\User;

final class DefaultTransactionCategory
{

    public function createToUser(User $user): void
    {
        collect($this->all())
            ->map(fn(array $category) => [...$category, 'user_id' => $user->id])->dd();
    }

    public function all(): array
    {
        return array_merge($this->incomes(), $this->expenses());
    }

    public function expenses(): array
    {
        return [
            ['name' => 'Навчання', 'type_id' => 1],
            ['name' => 'Подорож', 'type_id' => 1],
            ['name' => 'Транспорт', 'type_id' => 1],
            ['name' => "Здоров'я", 'type_id' => 1],
            ['name' => 'Рахунки', 'type_id' => 1],
            ['name' => 'Одяг', 'type_id' => 1],
            ['name' => 'Їжа', 'type_id' => 1, 'children' => [
                ['name' => 'Продукти', 'type_id' => 1],
                ['name' => 'Ресторани', 'type_id' => 1],
                ['name' => 'Фастфуд', 'type_id' => 1],
            ]],
            ['name' => 'Розваги', 'type_id' => 1],
            ['name' => 'Тварини', 'type_id' => 1],
            ['name' => 'Благодійність', 'type_id' => 1],
            ['name' => 'Електроніка', 'type_id' => 1],
            ['name' => 'Інше', 'type_id' => 1],
        ];
    }

    public function incomes(): array
    {
        return [
            ['name' => 'Зарплата', 'type_id' => 2],
            ['name' => 'Підробіток', 'type_id' => 2],
            ['name' => 'Продаж', 'type_id' => 2],
            ['name' => 'Подарунки', 'type_id' => 2],
            ['name' => 'Інше', 'type_id' => 2],
        ];
    }
}
