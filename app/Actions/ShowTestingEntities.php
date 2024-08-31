<?php
declare(strict_types=1);

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;

final class ShowTestingEntities
{
    public static function listTransaction(): void
    {
        self::run([
            \App\Models\Account::class,
            \App\Models\AccountSum::class,
            \App\Models\Transaction::class,
        ]);
    }

    public static function run(array $models): void
    {
        $data = [];
        /** @var Model $model */
        foreach ($models as $model) {
            $class = app($model);
            $data[$class->getTable()] = $class::all()->toArray();
        }

        dd($data);
    }
}
