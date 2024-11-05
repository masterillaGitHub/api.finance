<?php

namespace App\Http\Requests\BankAccount;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bankId' => 'integer|required|exists:banks,id',
            'accounts' => 'array|required',
            'accounts.*.name' => 'string|required',
            'accounts.*.creditLimit' => 'integer|nullable',
            'accounts.*.idOrigin' => 'string|required',
            'accounts.*.currencyCode' => 'integer|required|exists:currencies,numeric_code',
        ];
    }
}
