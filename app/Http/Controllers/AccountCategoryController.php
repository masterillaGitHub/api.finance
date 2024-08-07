<?php

namespace App\Http\Controllers;

use App\Repositories\AccountCategoryRepository as Repository;
use App\Http\Resources\AccountCategoryResource as Resource;
use App\Http\Resources\NormalizeResources\AnonymousResourceCollection;

class AccountCategoryController extends Controller
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

    public function mainPage(): AnonymousResourceCollection
    {
        $items = $this->repository->mainPage();

        return Resource::collection($items);
    }

    public function transactionPage(): AnonymousResourceCollection
    {
        $items = $this->repository->transactionPage();

        return Resource::collection($items);
    }
}
