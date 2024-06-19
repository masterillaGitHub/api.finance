<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function responseJsonData(array|int|string $data = []): \Illuminate\Http\JsonResponse
    {
        return response()->json(['data' => $data]);
    }
}
