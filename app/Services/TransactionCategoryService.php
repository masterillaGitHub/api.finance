<?php

namespace App\Services;

use App\Models\TransactionCategory as Model;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

class TransactionCategoryService
{
    /**
     * @throws Throwable
     */
    public function store(array $data): Model
    {
        $data['user_id'] = auth()->id();

        try {
            DB::beginTransaction();

            if (Arr::has($data, 'relationships')) {
                $relationships = Arr::pull($data, 'relationships');

                if (Arr::has($relationships, 'parent')) {
                    $data['parent_id'] = $relationships['parent'];
                }

                if (Arr::has($relationships, 'type')) {
                    $data['type_id'] = $relationships['type'];
                }
            }

            $cell = Model::firstOrCreate($data);

            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }

        return $cell;
    }

    /**
     * @throws Throwable
     */
    public function update(int $id, array $data): Model
    {
        $model = Model::query()
            ->where('id', $id)
            ->first();

        try {
            DB::beginTransaction();

            if (Arr::has($data, 'relationships')) {
                $relationships = Arr::pull($data, 'relationships');

                if (Arr::has($relationships, 'parent')) {
                    $data['parent_id'] = $relationships['parent'];
                }
            }

            $model->update($data);

            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }

        return $model;
    }
}
