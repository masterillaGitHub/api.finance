<?php

namespace App\Http\Controllers;

use App\Http\Resources\BankConnectionResource as Resource;
use App\Repositories\BankConnectionRepository as Repository;
use App\Http\Requests\BankConnection\StoreRequest as StoreRequest;
use App\Services\BankConnectionService as Service;

class BankConnectionController extends Controller
{
    private Service $service;
    private Repository $repository;

    public function __construct()
    {
        $this->service = new Service();
        $this->repository = new Repository();
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
