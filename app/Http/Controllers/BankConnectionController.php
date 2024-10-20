<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankConnection\StoreRequest as StoreRequest;
use App\Services\BankConnectionService as Service;
use Illuminate\Http\Response;

class BankConnectionController extends Controller
{
    private Service $service;

    public function __construct()
    {
        $this->service = new Service();
    }

    /**
     * @throws \Throwable
     */
    public function store(StoreRequest $request): Response
    {
        $data = $request->validated();
        $this->service->store($data);

        return response()->noContent();
    }
}
