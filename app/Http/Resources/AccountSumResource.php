<?php

namespace App\Http\Resources;

use App\Http\Resources\NormalizeResources\JsonResource;
use App\Models\AccountSum as Model;
use Illuminate\Database\Query\Builder;

class AccountSumResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        /** @var Model|Builder|JsonResource $this */

        return [
            'id' => $this->id,
            'balance' => $this->balance,
            'account' => AccountResource::make($this->whenLoaded('account')),
            'currency' => CurrencyResource::make($this->whenLoaded('currency')),
        ];
    }
}
