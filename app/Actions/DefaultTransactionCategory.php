<?php
declare(strict_types=1);

namespace App\Actions;

use App\Enums\TransactionType;

final class DefaultTransactionCategory
{

    public function all(): array
    {
        return array_merge($this->incomes(), $this->expenses());
    }

    public function expenses(): array
    {
        $typeId = TransactionType::EXPENSE->value;

        return [
            ['name' => 'Навчання', 'type_id' => $typeId],
            ['name' => 'Подорож', 'type_id' => $typeId],
            ['name' => 'Транспорт', 'type_id' => $typeId],
            ['name' => "Здоров'я", 'type_id' => $typeId],
            ['name' => 'Рахунки', 'type_id' => $typeId],
            ['name' => 'Одяг', 'type_id' => $typeId],
            ['name' => 'Їжа', 'type_id' => $typeId, 'children' => [
                ['name' => 'Продукти', 'type_id' => $typeId],
                ['name' => 'Ресторани', 'type_id' => $typeId],
                ['name' => 'Фастфуд', 'type_id' => $typeId],
            ]],
            ['name' => 'Розваги', 'type_id' => $typeId],
            ['name' => 'Тварини', 'type_id' => $typeId],
            ['name' => 'Благодійність', 'type_id' => $typeId],
            ['name' => 'Електроніка', 'type_id' => $typeId],
            ['name' => 'Інше', 'type_id' => $typeId],
        ];
    }

    public function incomes(): array
    {
        $typeId = TransactionType::INCOME->value;

        return [
            ['name' => 'Зарплата', 'type_id' => $typeId],
            ['name' => 'Підробіток', 'type_id' => $typeId],
            ['name' => 'Продаж', 'type_id' => $typeId],
            ['name' => 'Подарунки', 'type_id' => $typeId],
            ['name' => 'Інше', 'type_id' => $typeId],
        ];
    }
}
