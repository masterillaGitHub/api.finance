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
        $list = [
            ['name' => 'Авто'],
            ['name' => 'Навчання'],
            ['name' => 'Подорож'],
            ['name' => 'Транспорт'],
            ['name' => "Краса та Здоров'я"],
            ['name' => 'Рахунки'],
            ['name' => 'Комуналка та інтернет'],
            ['name' => 'Поповнення мобільного'],
            ['name' => 'Одяг та взуття'],
            ['name' => 'Кафе та ресторани'],
            ['name' => 'Продукти та супермаркети'],
            ['name' => 'Розваги та спорт'],
            ['name' => 'Книги'],
            ['name' => 'Тварини'],
            ['name' => 'Благодійність'],
            ['name' => 'Електроніка'],
            ['name' => 'Інше'],
        ];

        return $this->addTypesId($list, TransactionType::EXPENSE);
    }

    public function incomes(): array
    {
        $list = [
            ['name' => 'Зарплата'],
            ['name' => 'Підробіток'],
            ['name' => 'Продаж'],
            ['name' => 'Подарунки'],
            ['name' => 'Інше'],
        ];

        return $this->addTypesId($list, TransactionType::INCOME);
    }

    private function addTypesId(array $items, TransactionType $type): array
    {
        return array_map(fn (array $item) => $this->addTypeId($item, $type), $items);
    }

    private function addTypeId(array $item, TransactionType $type): array
    {
        if (isset($item['children'])) {
            $item['children'] = $this->addTypeId($item['children'], $type);
        }
        else {
            $item['type_id'] = $type->value;
        }

        return $item;
    }
}
