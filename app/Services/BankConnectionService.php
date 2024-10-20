<?php

namespace App\Services;

use App\Models\BankConnection as Model;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

class BankConnectionService
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

                if (Arr::has($relationships, 'bank')) {
                    $data['bank_id'] = $relationships['bank'];
                }
            }

            $cell = Model::updateOrCreate([
                'user_id' => $data['user_id'],
                'bank_id' => $data['bank_id'],
            ], $data);

            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }

        return $cell;
    }
}
