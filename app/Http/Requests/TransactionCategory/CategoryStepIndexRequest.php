<?php

namespace App\Http\Requests\TransactionCategory;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStepIndexRequest extends FormRequest
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
            'type_id' => ['integer', 'required', 'exists:transaction_types,id'],
        ];
    }
}
