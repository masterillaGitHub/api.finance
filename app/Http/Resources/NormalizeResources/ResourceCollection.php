<?php

namespace App\Http\Resources\NormalizeResources;

use Illuminate\Http\Resources\Json\ResourceCollection as BaseCollection;

/**
 * Клас для нормалізації даних в арі ресурсі колекції
 */
class ResourceCollection extends BaseCollection
{
    use NormalizesResource;
}
