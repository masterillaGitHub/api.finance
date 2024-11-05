<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankAccount\StoreRequest as Request;
use App\Services\AccountSumService;
use App\Services\BankAccountService as Service;
use Illuminate\Http\Response;
use Throwable;

class BankAccountController extends Controller
{
    private Service $service;
    private AccountSumService $accountSumService;

    public function __construct()
    {
        $this->service = new Service();
        $this->accountSumService = new AccountSumService();
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request): Response
    {
        $data = $request->validated();

        $this->service->storeAccounts($data);

        return response()->noContent();
    }
}
