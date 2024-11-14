<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankAccount\StoreRequest as StoreRequest;
use App\Http\Requests\BankAccount\UpdateTransactionRequest as UpdateTransaction;
use App\Services\BankAccountService as Service;
use Illuminate\Http\Response;
use Throwable;

class BankAccountController extends Controller
{
    private Service $service;

    public function __construct()
    {
        $this->service = new Service();
    }

    /**
     * @throws Throwable
     */
    public function store(StoreRequest $request): Response
    {
        $data = $request->validated();

        $this->service->storeAccounts($data);

        return response()->noContent();
    }

    public function updateTransactions(UpdateTransaction $request): Response
    {
        $data = $request->validated();

        $this->service->updateTransactions($data);

        return response()->noContent();
    }
}
