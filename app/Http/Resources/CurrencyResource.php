<?php

namespace App\Http\Resources;

use App\Http\Resources\NormalizeResources\JsonResource;
use App\Models\Currency as Model;
use Illuminate\Database\Query\Builder;

class CurrencyResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        /** @var Model|Builder|JsonResource $this */

        return [
            'id' => $this->id,
            'name' => $this->name,
            'symbol' => $this->symbol,
            'alphabetic_code' => $this->alphabetic_code,
            'numeric_code' => $this->numeric_code,
            'number_decimals' => $this->number_decimals,
        ];
    }
}
