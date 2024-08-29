<?php
declare(strict_types=1);

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;

final class ShowAllEntities
{
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
