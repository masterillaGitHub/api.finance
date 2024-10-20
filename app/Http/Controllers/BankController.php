<?php

namespace App\Http\Controllers;

use App\Http\Resources\BankResource as Resource;
use App\Http\Resources\NormalizeResources\AnonymousResourceCollection;
use App\Repositories\BankRepository as Repository;

class BankController extends Controller
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
