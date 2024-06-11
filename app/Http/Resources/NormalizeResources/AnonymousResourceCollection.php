<?php

namespace App\Http\Resources\NormalizeResources;

class AnonymousResourceCollection extends ResourceCollection
{
    /**
     * The name of the resource being collected.
     *
     * @var string
     */
    public $collects;

    protected string $resourceName;

    public function __construct(mixed $resource, string $collects)
    {
        $this->collects = $collects;

        // Copy normalizr entity key
        $this->resourceName = (new $collects(NULL))->getResourceName();

        parent::__construct($resource);
    }
}
