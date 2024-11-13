<?php

namespace App\Services;

use App\Enums\TransactionInputType;
use App\Enums\TransactionType;
use App\Events\Transaction\TransactionCreated;
use App\Events\Transaction\TransactionDestroyed;
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
        $data['input_type'] = TransactionInputType::MANUAL->value;

        try {
            DB::beginTransaction();

            if (Arr::has($data['relationships'], 'tags')) {
                $tagIds = Arr::pull($data['relationships'], 'tags');
            }

            $data = $this->preparationData($data);

            $item = Model::create($data);

            if (isset($tagIds)) {
                $item->tags()->sync($tagIds);
            }

            if ($this->isTransferTransaction($item)) {
                $this->updateTransferTransactionId($item);
            }

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

            if (Arr::has($data['relationships'], 'tags')) {
                $tagIds = Arr::pull($data['relationships'], 'tags');
            }

            $data = $this->preparationData($data);

            $model->fill($data);

            $model->save();

            if (isset($tagIds)) {
                $model->tags()->sync($tagIds);
            }

            TransactionUpdated::dispatch($model, $data);

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
    public function destroy(Model $item): void
    {
        try {
            DB::beginTransaction();

            $tempItem = $item;
            $item->delete();

            TransactionDestroyed::dispatch($tempItem);

            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }

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

            if (Arr::has($relationships, 'transfer_transaction')) {
                $data['transfer_transaction_id'] = $relationships['transfer_transaction'];
            }
        }

        return $data;
    }

    private function isTransferTransaction(Model $transaction): bool
    {
        return $transaction->type_id === TransactionType::TRANSFER->value
            && !empty($transaction->transfer_transaction_id);
    }

    private function updateTransferTransactionId(Model $transaction): void
    {
        $transferTransaction = Model::find($transaction->transfer_transaction_id);

        $transferTransaction->transfer_transaction_id = $transaction->id;

        $transferTransaction->save();
    }
}
