<?php

namespace App\Services;

use App\Events\AccountSum\AccountSumCreated;
use App\Events\AccountSum\AccountSumUpdated;
use App\Models\AccountSum as Model;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

class AccountSumService
{
    /**
     * @throws Throwable
     */
    public function store(array $data): Model
    {
        try {
            DB::beginTransaction();

            if (Arr::has($data, 'relationships')) {
                $relationships = Arr::pull($data, 'relationships');

                if (Arr::has($relationships, 'account')) {
                    $data['account_id'] = $relationships['account'];
                }

                if (Arr::has($relationships, 'currency')) {
                    $data['currency_id'] = $relationships['currency'];
                }
            }

            $model = Model::create($data);

            AccountSumCreated::dispatch($model, $data);

            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }

        return $model;
    }

    /**
     * @throws Throwable
     */
    public function update(int $id, array $data): Model
    {
        $model = Model::find($id);

        try {
            DB::beginTransaction();

            $model->fill($data);

            AccountSumUpdated::dispatch($model, $data);

            $model->save();

            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }

        return $model;
    }
}
