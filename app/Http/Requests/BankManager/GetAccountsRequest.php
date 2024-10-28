<?php

namespace App\Http\Requests\BankManager;

use Illuminate\Foundation\Http\FormRequest;

class GetAccountsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bankId' => ['integer', 'required', 'exists:banks,id'],
        ];
    }
}
