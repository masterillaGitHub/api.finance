<?php

namespace App\Http\Resources;

use App\Http\Resources\NormalizeResources\JsonResource;
use App\Models\Account as Model;
use Illuminate\Database\Query\Builder;

class AccountResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        /** @var Model|Builder|JsonResource $this */

        return [
            'id' => $this->id,
            'name' => $this->whenHas('name', $this->name),
            'input_type' => $this->whenHas('input_type', $this->input_type),
            'bank_name' => $this->whenHas('bank_name', $this->bank_name),
            'credit_limit' => $this->whenHas('credit_limit', $this->credit_limit),
            'category' => AccountCategoryResource::make($this->whenLoaded('category')),
            'currency' => CurrencyResource::make($this->whenLoaded('currency')),
            'sums' => AccountSumResource::collection($this->whenLoaded('sums')),
        ];
    }
}
