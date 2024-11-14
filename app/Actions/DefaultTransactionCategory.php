<?php
declare(strict_types=1);

namespace App\Actions;

use App\Enums\TransactionTypeEnum;

final class DefaultTransactionCategory
{

    public function all(): array
    {
        return array_merge($this->incomes(), $this->expenses());
    }

    public function expenses(): array
    {
        $list = [
            ['name' => 'Авто', 'icon' => 'mdi-car'],
            ['name' => 'Навчання', 'icon' => 'mdi-school-outline'],
            ['name' => 'Подорож', 'icon' => 'mdi-bag-suitcase'],
            ['name' => 'Транспорт', 'icon' => 'mdi-train-car'],
            ['name' => "Краса та Здоров'я", 'icon' => 'mdi-heart-pulse'],
            ['name' => 'Рахунки', 'icon' => 'mdi-invoice-text-multiple-outline'],
            ['name' => 'Комуналка та інтернет', 'icon' => 'mdi-home-lightbulb-outline'],
            ['name' => 'Поповнення мобільного', 'icon' => 'mdi-cellphone-basic'],
            ['name' => 'Одяг та взуття', 'icon' => 'mdi-tshirt-crew-outline'],
            ['name' => 'Кафе та ресторани', 'icon' => 'mdi-silverware-fork-knife'],
            ['name' => 'Продукти та супермаркети', 'icon' => 'mdi-cart'],
            ['name' => 'Розваги та спорт', 'icon' => 'mdi-human-scooter'],
            ['name' => 'Книги', 'icon' => 'mdi-book-open-page-variant-outline'],
            ['name' => 'Тварини', 'icon' => 'mdi-google-downasaur'],
            ['name' => 'Благодійність', 'icon' => 'mdi-charity'],
            ['name' => 'Електроніка', 'icon' => 'mdi-television-classic'],
            ['name' => 'Інше', 'icon' => 'mdi-dots-horizontal'],
        ];

        return $this->addTypesId($list, TransactionTypeEnum::EXPENSE);
    }

    public function incomes(): array
    {
        $list = [
            ['name' => 'Зарплата', 'icon' => 'mdi-bank-transfer-out'],
            ['name' => 'Підробіток', 'icon' => 'mdi-hand-coin-outline'],
            ['name' => 'Продаж', 'icon' => 'mdi-package-variant-closed'],
            ['name' => 'Подарунки', 'icon' => 'mdi-gift-open-outline'],
            ['name' => 'Інше', 'icon' => 'mdi-dots-horizontal'],
        ];

        return $this->addTypesId($list, TransactionTypeEnum::INCOME);
    }

    private function addTypesId(array $items, TransactionTypeEnum $type): array
    {
        return array_map(fn (array $item) => $this->addTypeId($item, $type), $items);
    }

    private function addTypeId(array $item, TransactionTypeEnum $type): array
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
