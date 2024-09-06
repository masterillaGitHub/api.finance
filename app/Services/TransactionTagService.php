<?php

namespace App\Services;

use App\Models\TransactionTag as Model;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class TransactionTagService
{
    /**
     * @throws Throwable
     */
    public function store(array $data): Model
    {
        $data['user_id'] = auth()->id();

        try {
            DB::beginTransaction();

            $item = Model::create($data);

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

            $model->fill($data);

            $model->save();

            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }

        return $model;
    }

    public function setSorting(array $orderNumbers): void
    {
        Model::setNewOrder($orderNumbers);
    }
}
