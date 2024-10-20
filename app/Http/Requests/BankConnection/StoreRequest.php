<?php

namespace App\Http\Requests\BankConnection;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['string', 'nullable'],
            'relationships' => ['array', 'nullable'],
            'relationships.bank' => ['integer', 'required', 'exists:banks,id'],
        ];
    }
}
