<?php

namespace App\Http\Controllers;

use App\Repositories\AccountRepository as Repository;
use App\Http\Resources\AccountResource as Resource;
use App\Http\Requests\Account\StoreRequest as StoreRequest;
use App\Http\Requests\Account\UpdateRequest as UpdateRequest;
use App\Services\AccountService as Service;
use App\Http\Resources\NormalizeResources\AnonymousResourceCollection;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AccountController extends Controller
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

    public function show(Account $account): Resource
    {
        $item = $this->repository->whereId($account->id);

        return Resource::make($item);
    }

    /**
     * @throws \Throwable
     */
    public function update(UpdateRequest $request, Account $account): Resource
    {
        $data = $request->validated();

        $item = $this->service->update($account, $data);
        $item = $this->repository->whereId($item->id);

        return Resource::make($item);
    }

    public function destroy(Account $account): Response
    {
        $account->delete();

        return response()->noContent();
    }

    public function balanceTotal(): JsonResponse
    {
        $balanceTotal = Account::query()
            ->withSum('sums', 'balance')
            ->where('user_id', auth()->id())
            ->get()
            ->sum('sums_sum_balance');

        return $this->responseJsonData($balanceTotal);
    }
}
