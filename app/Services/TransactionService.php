<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Events\Transaction\TransactionCreated;
use App\Events\Transaction\TransactionUpdated;
use App\Models\Transaction as Model;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

class TransactionService
{
    /**
     * @throws Throwable
     */
    public function store(array $data): Model
    {
        $data['user_id'] = auth()->id();

        try {
            DB::beginTransaction();

            $data = $this->preparationData($data);

            $item = Model::create($data);

            TransactionCreated::dispatch($item);

            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }

        return $item;
    }

    /**
     * @throws Throwable
     */
    public function update(Model $model, array $data): Model
    {
        try {
            DB::beginTransaction();

            $data = $this->preparationData($data);

            $model->fill($data);

            TransactionUpdated::dispatch($model, $data);

            $model->save();

            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }

        return $model;
    }

    private function preparationData(array $data): array
    {
        if (Arr::has($data, 'relationships')) {
            $relationships = Arr::pull($data, 'relationships');

            if (Arr::has($relationships, 'type')) {
                $data['type_id'] = $relationships['type'];
            }

            if (Arr::has($relationships, 'account')) {
                $data['account_id'] = $relationships['account'];
            }

            if (Arr::has($relationships, 'category')) {
                $data['category_id'] = $relationships['category'];
            }

            if (Arr::has($relationships, 'currency')) {
                $data['currency_id'] = $relationships['currency'];
            }

            if (Arr::has($relationships, 'to_account')) {
                $data['to_account_id'] = $relationships['to_account'];
            }

            if (Arr::has($relationships, 'to_currency')) {
                $data['to_currency_id'] = $relationships['to_currency'];
            }
        }

        return $data;
    }
}
