<?php

namespace Modules\AdminManagement\Http\Requests;

use App\Http\Requests\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_type' => 'sometimes|required|in:received,paid',
            'amount' => 'sometimes|required|numeric|min:0.01',
            'currency' => 'nullable|string|max:10',
            'payment_method' => 'sometimes|required|string|max:100',
            'transaction_id' => 'nullable|string|max:255',
            'payment_date' => 'sometimes|required|date',
            'status' => 'nullable|in:completed,pending,failed,refunded',
            'client_id' => 'nullable|exists:clients,id',
            'vendor_id' => 'nullable|exists:sellers,id',
            'order_item_id' => 'nullable|exists:order_items,id',
            'notes' => 'nullable|string',
        ];
    }
}
