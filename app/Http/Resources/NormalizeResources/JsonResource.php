<?php

namespace App\Http\Resources\NormalizeResources;

use Illuminate\Http\Resources\Json\JsonResource as BaseResource;

/**
 * Клас для нормалізації даних в арі ресурсі сутності
 * @method relationLoaded(int|string $field)
 */
class JsonResource extends BaseResource
{
    use NormalizesResource;

    public static function collection($resource): AnonymousResourceCollection
    {
        return new AnonymousResourceCollection($resource, static::class);
    }
}
