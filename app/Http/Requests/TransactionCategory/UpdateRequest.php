<?php

namespace App\Http\Requests\TransactionCategory;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        return [
            'name' => 'string|required',
            'icon' => 'string|required',
            'relationships' => ['array', 'nullable'],
            'relationships.parent' => [
                'integer',
                'nullable',
                Rule::exists('transaction_categories', 'id')
                    ->where(fn (Builder $query) =>
                        $query->whereNull('parent_id')
                    ),
            ],
        ];
    }
}
