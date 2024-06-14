<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountSum\StoreRequest as StoreRequest;
use App\Services\AccountSumService as Service;
use App\Http\Resources\AccountSumResource as Resource;
use App\Http\Resources\NormalizeResources\AnonymousResourceCollection;
use App\Repositories\AccountSumRepository as Repository;

class AccountSumsController extends Controller
{
    private Repository $repository;
    private Service $service;

    public function __construct()
    {
        $this->repository = new Repository();
        $this->service = new Service();
    }

    public function index(): AnonymousResourceCollection
    {
        $items = $this->repository->get();

        return Resource::collection($items);
    }

    /**
     * @throws \Throwable
     */
    public function store(StoreRequest $request): Resource
    {
        $data = $request->validated();

        $item = $this->service->store($data);
        $item = $this->repository->whereId($item->id);

        return Resource::make($item);
    }
}
