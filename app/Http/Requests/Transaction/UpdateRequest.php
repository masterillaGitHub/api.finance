<?php

namespace App\Http\Requests\Transaction;

use App\Enums\TransactionType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        $requiredIfTypeTransfer = 'required_if:relationships.type,' . TransactionType::TRANSFER->value;

        return [
            'amount' => 'integer|required',
            'note' => 'string|nullable',
            'to_amount' => ['integer', $requiredIfTypeTransfer, 'nullable'],
            'transaction_at' => 'date|nullable',
            'relationships' => ['array', 'nullable'],
            'relationships.type' => ['integer', 'required', 'exists:transaction_types,id'],
            'relationships.account' => ['integer', 'required', 'exists:accounts,id'],
            'relationships.category' => ['integer', 'required', 'exists:transaction_categories,id'],
            'relationships.currency' => ['integer', 'required', 'exists:currencies,id'],
            'relationships.to_account' => ['integer', $requiredIfTypeTransfer, 'nullable', 'exists:accounts,id'],
            'relationships.to_currency' => ['integer', $requiredIfTypeTransfer, 'nullable', 'exists:currencies,id'],
            'relationships.tags' => ['array', 'nullable'],
            'relationships.tags.*' => ['integer', 'required', 'exists:transaction_tags,id'],
        ];
    }
}
