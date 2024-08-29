<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionCategory\CategoryStepIndexRequest;
use App\Http\Requests\TransactionCategory\StoreRequest as StoreRequest;
use App\Http\Requests\TransactionCategory\UpdateRequest as UpdateRequest;
use App\Http\Resources\NormalizeResources\AnonymousResourceCollection;
use App\Http\Resources\TransactionCategoryResource as Resource;
use App\Models\TransactionCategory;
use App\Repositories\TransactionCategoryRepository as Repository;
use App\Services\TransactionCategoryService as Service;
use Illuminate\Http\Response;

class TransactionCategoryController extends Controller
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

    public function categoryStepIndex(CategoryStepIndexRequest $request): AnonymousResourceCollection
    {
        $data = $request->validated();
        $items = $this->repository->categoryStepIndex($data['type_id']);

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

    public function destroy(TransactionCategory $transactionCategory): Response
    {
        $transactionCategory->delete();

        return response()->noContent();
    }
}
