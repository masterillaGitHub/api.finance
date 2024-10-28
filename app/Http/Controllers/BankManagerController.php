<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankManager\GetAccountsRequest;
use App\Services\BankManager\BankManagerService as Service;
use Illuminate\Http\JsonResponse;

class BankManagerController extends Controller
{
    private Service $service;

    public function __construct()
    {
        $this->service = new Service();
    }

    /**
     * @throws \Exception
     */
    public function getAccounts(GetAccountsRequest $request): JsonResponse
    {
        $data = $request->validated();

        $items = $this->service->getAccountsByBankId($data['bankId']);

        return response()->json([
            'data' => $items
        ]);
    }
}
