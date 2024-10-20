<?php

namespace App\Http\Resources;

use App\Http\Resources\NormalizeResources\JsonResource;
use App\Models\BankConnection as Model;
use Illuminate\Database\Query\Builder;

class BankConnectionResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        /** @var Model|Builder|JsonResource $this */

        return [
            'id' => $this->id,
            'bank' => BankResource::make($this->whenLoaded('bank')),
        ];
    }
}
