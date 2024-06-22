<?php

namespace App\Http\Resources;

use App\Http\Resources\NormalizeResources\JsonResource;
use App\Models\TransactionType as Model;
use Illuminate\Database\Query\Builder;

class TransactionTypeResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        /** @var Model|Builder|JsonResource $this */

        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
