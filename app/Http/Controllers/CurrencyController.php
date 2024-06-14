<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyResource as Resource;
use App\Http\Resources\NormalizeResources\AnonymousResourceCollection;
use App\Repositories\CurrencyRepository as Repository;

class CurrencyController extends Controller
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = new Repository();
    }

    public function index(): AnonymousResourceCollection
    {
        $items = $this->repository->get();

        return Resource::collection($items);
    }
}
