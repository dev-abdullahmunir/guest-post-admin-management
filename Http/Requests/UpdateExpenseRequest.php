<?php

namespace Modules\AdminManagement\Http\Requests;

use App\Http\Requests\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:100',
            'amount' => 'sometimes|required|numeric|min:0.01',
            'currency' => 'nullable|string|max:10',
            'expense_date' => 'sometimes|required|date',
            'payment_method' => 'sometimes|required|string|max:100',
            'description' => 'nullable|string',
        ];
    }
}
