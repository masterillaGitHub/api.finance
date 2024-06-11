<?php

namespace App\Http\Resources\NormalizeResources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait NormalizesResource
{
    public bool $preserveKeys = true;

    /*
     * Назва ресурсу вкладених зв'язків
     */
    protected string $resourceName;
    /*
     * Назва первинного ключа, по можливо ідентифікувати сутність
     */
    protected string $primaryKey = 'id';

    /**
     * Чи використовувати обгортку для зв'язків
     */
    protected bool $isIncludedInSeparateField = true;
    /*
     * Назва обгортки зв'язків
     */
    protected string $includedInSeparateFieldName = 'relationships';
    /*
     * Чи вказувати назву ресурсу разом із ідентифікацією зв'язка
     */
    protected bool $isReturnType = false;
    /*
     * Чи вказувати ім'я масиву як ідентифікатор сутності
     */
    protected bool $includedUseId = true;
    /*
     * Чи нормалізувати дані
     */
    protected bool $isNormalize = true;
    /*
     * true - в масиві 'data' вказується основний (той що викликається) ресурс(и)
     * false - в масиві 'data' вказуються тільки ідентифікатори основних ресурсів(у)
     */
    protected bool $isMainResource = false;

    /**
     * true - Всі ресурси використовують назву поля для ідентифікатора "id"
     * false - Кожен ресурс використовує назву ідентифікатора який вказаний у $primaryKey
     */
    protected bool $uniformIdentifier = true;

    private array $included = [];
    private array $mainResource = [];

    public function with($request): array
    {
        if ($this->isNormalize) {
            return [
                'included' => $this->included,
            ];
        }

        return [];
    }

    /**
     * @throws \Exception
     */
    public function resolve($request = null): array|int|string|null
    {
        if (!$this->isNormalize) {
            return parent::resolve($request);
        }

        if ($this->emptyResource()) {
            return $this->resource instanceof Collection ? [] : null;
        }

        if ($this instanceof ResourceCollection) {
            $ids = $this->walkItems($this->resource, $request, $this->isMainResource);
        }
        else {
            $ids = $this->walkItem($this, $request, $this->isMainResource);
        }

        if (!$this->isMainResource) {
            return $ids;
        }

        if ($this->resource instanceof Model) {
            return [...$this->mainResource][0];
        }

        return [...$this->mainResource];
    }

    public function getResourceName(): string
    {
        return $this->resourceName ?? $this->classToResourceName(get_class($this));
    }

    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * @return array|int|string ArrayShape(['type' => "string", 'id' => "int"])
     * @throws \Exception
     */
    protected function walkItem(
        JsonResource $resource,
        Request      $request,
        bool         $isMainResource = false
    ): int|array|string
    {
        $item = $this->filter($resource->toArray($request));
        $primaryKey = $resource->getPrimaryKey();
        $itemId = $this->uniformIdentifier ? 'id' : $primaryKey;
        $resourceName = $resource->getResourceName();

        if (!isset($item[$primaryKey])) {
            throw new \Exception("Primary key [$primaryKey] is not found in api normalize resource [$resourceName]");
        }

        if ($this->uniformIdentifier && $itemId !== $primaryKey) {
            $item[$itemId] = $item[$primaryKey];
            unset($item[$primaryKey]);
        }

        $item = $this->handlerRelationFields($resource, $request, $item);

        if ($isMainResource) {
            $this->mainResource[] = $item;
        } else {
            if (!isset($this->included[$resourceName])) {
                $this->included[$resourceName] = [];
            }

            if ($this->includedUseId) {
                $this->included[$resourceName][$item[$itemId]] = $item;
            } else {
                if (!$this->isExitObject($item[$itemId], $itemId, $this->included[$resourceName])) {
                    $this->included[$resourceName][] = $item;
                }
            }
        }

        if ($this->isReturnType) {
            return [
                'type' => $resourceName,
                $itemId => $item[$itemId]
            ];
        }

        return $item[$itemId];
    }

    /**
     * @throws \Exception
     */
    protected function walkItems(
        $resources,
        mixed $request,
        bool $isMainResource = false
    ): array
    {
        $result = [];

        foreach ($resources as $resource) {
            $result[] = $this->walkItem(
                $resource,
                $request,
                $isMainResource
            );
        }

        return $result;
    }

    protected function classToResourceName(string $class): string
    {
        $className = Str::of($class)->explode('\\')->pop();

        return Str::of($className)
            ->replace(['Resource', 'Collection'], '')
            ->plural()
            ->snake()
            ->toString();
    }

    protected function relationToKeyName($key): string
    {
        return Str::plural($key);
    }

    /**
     * @throws \Exception
     */
    private function normalizeRelationship(
        Request                         $request,
        JsonResource|ResourceCollection $resource
    ): int|array|string
    {
        $relationName = $resource->getResourceName();

        if (!isset($this->included[$relationName])) {
            $this->included[$relationName] = [];
        }

        if ($resource instanceof ResourceCollection) {
            // Normalize collection relationship
            $newKey = $this->walkItems(
                $resource,
                $request
            );
        } else {
            // Normalize resource relationship
            $newKey = $this->walkItem(
                $resource,
                $request
            );
        }

        return $newKey;
    }

    /**
     * @throws \Exception
     */
    private function handlerRelationFields(JsonResource $resource, Request $request, array $item): array
    {
        foreach ($item as $field => $value) {
            if (!$resource->relationLoaded($field)) {
                continue;
            }

            $item = $this->handlerRelationField($request, $item, $field, $value);
        }

        return $item;
    }

    /**
     * @throws \Exception
     */
    private function handlerRelationField(Request $request, array $item, string $field, mixed $value): array
    {
        if ($this->isValueMissing($value)) {
            unset($item[$field]);
            return $item;
        }

        if (!empty($value) && $this->isValueResource($value)) {
            $value = $this->normalizeRelationship($request, $value);
        }

        if ($this->isIncludedInSeparateField) {
            $item[$this->includedInSeparateFieldName][$field] = $value;
            unset($item[$field]);

            return $item;
        }

        $item[$field] = $value;

        return $item;

    }

    private function isValueResource(mixed $value): bool
    {
        return $this->isResource($value) || $this->isResourceCollection($value);
    }

    private function isResource(mixed $value): bool
    {
        return $value instanceof JsonResource;
    }

    private function isResourceCollection(mixed $value): bool
    {
        return $value instanceof ResourceCollection;
    }

    private function isValueMissing(mixed $value): bool
    {
        return ($value->resource ?? null) instanceof MissingValue;
    }

    private function isExitObject(int|string $id, string $primaryKey, array $included): bool
    {
        return (bool)(array_column($included, null, $primaryKey)[$id] ?? false);
    }

    private function emptyResource(): bool
    {
        return ($this->resource instanceof Collection && $this->resource->isEmpty()) || empty($this->resource);
    }
}
