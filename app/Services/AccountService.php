<?php

namespace App\Services;

use App\Models\Account as Model;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

class AccountService
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

                if (Arr::has($relationships, 'category')) {
                    $data['category_id'] = $relationships['category'];
                }

                if (Arr::has($relationships, 'currency')) {
                    $data['currency_id'] = $relationships['currency'];
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
    public function update(Model $model, array $data): Model
    {
        try {
            DB::beginTransaction();

            if (Arr::has($data, 'relationships')) {
                Arr::pull($data, 'relationships');
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
