<?php

namespace App\Http\Resources;

use App\Http\Resources\NormalizeResources\JsonResource;
use App\Models\Transaction as Model;
use Illuminate\Database\Query\Builder;

class TransactionResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        /** @var Model|Builder|JsonResource $this */

        return [
            'id' => $this->id,
            'input_type' => $this->input_type,
            'amount' => $this->amount,
            'note' => $this->note,
            'description' => $this->description,
            'accrual_at' => $this->accrual_at?->format('d.m.Y H:i:s'),
            'transaction_at' => $this->transaction_at->format('d.m.Y H:i:s'),
            'transaction_at_date' => $this->transaction_at->format('d.m.Y'),
            'transaction_at_timestamp' => $this->transaction_at->timestamp,
            'to_amount' => (int) $this->to_amount,
            'user' => UserResource::make($this->whenLoaded('user')),
            'type' => TransactionTypeResource::make($this->whenLoaded('type')),
            'account' => AccountResource::make($this->whenLoaded('account')),
            'category' => TransactionCategoryResource::make($this->whenLoaded('category')),
            'currency' => CurrencyResource::make($this->whenLoaded('currency')),
            'to_account' => AccountResource::make($this->whenLoaded('to_account')),
            'to_currency' => CurrencyResource::make($this->whenLoaded('to_currency')),
        ];
    }
}
