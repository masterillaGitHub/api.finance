<?php

namespace App\Http\Resources;

use App\Http\Resources\NormalizeResources\JsonResource;
use App\Models\Tag as Model;
use Illuminate\Database\Query\Builder;

class TagResource extends JsonResource
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
