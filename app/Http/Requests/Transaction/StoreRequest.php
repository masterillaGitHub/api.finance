<?php

namespace App\Http\Requests\Transaction;

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
            'amount' => 'integer|required',
            'description' => 'string|nullable',
            'note' => 'string|nullable',
            'transaction_at' => 'date|nullable',
            'account_transfer_id' => 'integer|nullable|exists:accounts,id',

            'relationships' => ['array', 'nullable'],
            'relationships.type' => ['integer', 'required', 'exists:transaction_types,id'],
            'relationships.account' => ['integer', 'required', 'exists:accounts,id'],
            'relationships.category' => ['integer', 'required', 'exists:transaction_categories,id'],
            'relationships.currency' => ['integer', 'required', 'exists:currencies,id'],
            'relationships.transfer_transaction' => ['integer', 'nullable', 'exists:transactions,id'],
            'relationships.tags' => ['array', 'nullable'],
            'relationships.tags.*' => ['integer', 'required', 'exists:transaction_tags,id'],
        ];
    }
}
