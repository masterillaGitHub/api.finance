<?php

namespace App\Http\Resources;

use App\Http\Resources\NormalizeResources\JsonResource;
use App\Models\TransactionCategory as Model;
use Illuminate\Database\Query\Builder;

class TransactionCategoryResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        /** @var Model|Builder|JsonResource $this */

        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'type_id' => $this->type_id,
            'user_id' => $this->user_id,
            'user' => UserResource::make($this->whenLoaded('user')),
            'type' => TransactionTypeResource::make($this->whenLoaded('type')),
            'parent' => TransactionCategoryResource::make($this->whenLoaded('parent')),
            'children' => TransactionCategoryResource::collection($this->whenLoaded('children')),
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
        ];
    }
}
