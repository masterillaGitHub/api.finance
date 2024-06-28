<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'note' => 'string|nullable',
            'transaction_at' => 'date|nullable',
            'relationships' => ['array', 'nullable'],
            'relationships.type' => ['integer', 'required', 'exists:transaction_types,id'],
            'relationships.account' => ['integer', 'required', 'exists:accounts,id'],
            'relationships.category' => ['integer', 'required', 'exists:transaction_categories,id'],
            'relationships.currency' => ['integer', 'required', 'exists:currencies,id'],
        ];
    }
}
