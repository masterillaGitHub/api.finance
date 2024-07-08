<?php

namespace App\Http\Controllers;

use App\Events\TransactionCreated;
use App\Http\Requests\Transaction\StoreRequest as StoreRequest;
use App\Http\Requests\Transaction\UpdateRequest as UpdateRequest;
use App\Http\Resources\NormalizeResources\AnonymousResourceCollection;
use App\Http\Resources\TransactionResource as Resource;
use App\Models\Transaction;
use App\Repositories\TransactionRepository as Repository;
use App\Services\TransactionService as Service;
use Illuminate\Http\Response;

class TransactionController extends Controller
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
        $items = $this->repository->userIndex();

        return Resource::collection($items);
    }

    /**
     * @throws \Throwable
     */
    public function store(StoreRequest $request): Resource
    {
        $data = $request->validated();

        $item = $this->service->store($data);

        TransactionCreated::dispatch($item);

        $item = $this->repository->whereId($item->id);

        return Resource::make($item);
    }

    public function show(int $id): Resource
    {
        $item = $this->repository->whereId($id);

        return Resource::make($item);
    }

    /**
     * @throws \Throwable
     */
    public function update(UpdateRequest $request, int $id): Resource
    {
        $data = $request->validated();

        $item = $this->service->update($id, $data);
        $item = $this->repository->whereId($item->id);

        return Resource::make($item);
    }

    public function destroy(Transaction $transaction): Response
    {
        $transaction->delete();

        return response()->noContent();
    }
}
