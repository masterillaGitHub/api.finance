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
}
