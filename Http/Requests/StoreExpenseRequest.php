<?php

namespace Modules\AdminManagement\Http\Requests;

use App\Http\Requests\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|string|max:10',
            'expense_date' => 'required|date',
            'payment_method' => 'required|string|max:100',
            'description' => 'nullable|string',
        ];
    }
}
