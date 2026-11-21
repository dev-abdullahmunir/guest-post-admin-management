<?php

namespace Modules\AdminManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Shared\Models\Payment;
use Modules\Shared\Models\Client;
use Modules\Shared\Models\Seller;
use Modules\Shared\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    /**
     * Render the Payments Ledger Blade view.
     */
    public function renderView($viewFactory)
    {
        try {
            $payments = Payment::with(['client', 'vendor', 'orderItem.website'])->latest('payment_date')->get()->toArray();
            $clients = Client::orderBy('name')->get()->toArray();
            $sellers = Seller::orderBy('name')->get()->toArray();
            $orderItems = OrderItem::with(['order', 'website'])->latest()->get()->toArray();
        } catch (\Exception $e) {
            $payments = [];
            $clients = [];
            $sellers = [];
            $orderItems = [];
        }

        return $viewFactory->make('payments.index', [
            'payments' => $payments,
            'clients' => $clients,
            'sellers' => $sellers,
            'orderItems' => $orderItems,
        ]);
    }

    /**
     * Store a newly created payment.
     */
    public function store(array $body): array
    {
        if (empty($body['payment_type']) || !isset($body['amount']) || empty($body['payment_date']) || empty($body['payment_method'])) {
            http_response_code(422);
            return ['status' => 'error', 'message' => 'Payment type, amount, payment date, and method are required.'];
        }

        $payment = Payment::create([
            'order_item_id' => !empty($body['order_item_id']) ? (int)$body['order_item_id'] : null,
            'client_id' => !empty($body['client_id']) ? (int)$body['client_id'] : null,
            'vendor_id' => !empty($body['vendor_id']) ? (int)$body['vendor_id'] : null,
            'payment_type' => $body['payment_type'],
            'amount' => (float)$body['amount'],
            'currency' => !empty($body['currency']) ? strtoupper(trim($body['currency'])) : 'USD',
            'payment_method' => trim($body['payment_method']),
            'transaction_id' => trim($body['transaction_id'] ?? null) ?: null,
            'payment_date' => $body['payment_date'],
            'status' => $body['status'] ?? 'completed',
            'notes' => trim($body['notes'] ?? null) ?: null,
        ]);

        return [
            'status' => 'success',
            'message' => 'Payment recorded successfully!',
            'data' => $payment->load(['client', 'vendor', 'orderItem.website'])
        ];
    }

    /**
     * Show a specific payment.
     */
    public function show(int $id): array
    {
        $payment = Payment::with(['client', 'vendor', 'orderItem.website'])->findOrFail($id);
        return [
            'status' => 'success',
            'data' => $payment
        ];
    }

    /**
     * Update a payment.
     */
    public function update(int $id, array $body): array
    {
        $payment = Payment::findOrFail($id);

        $payment->update([
            'order_item_id' => array_key_exists('order_item_id', $body) ? (!empty($body['order_item_id']) ? (int)$body['order_item_id'] : null) : $payment->order_item_id,
            'client_id' => array_key_exists('client_id', $body) ? (!empty($body['client_id']) ? (int)$body['client_id'] : null) : $payment->client_id,
            'vendor_id' => array_key_exists('vendor_id', $body) ? (!empty($body['vendor_id']) ? (int)$body['vendor_id'] : null) : $payment->vendor_id,
            'payment_type' => $body['payment_type'] ?? $payment->payment_type,
            'amount' => isset($body['amount']) ? (float)$body['amount'] : $payment->amount,
            'currency' => !empty($body['currency']) ? strtoupper(trim($body['currency'])) : $payment->currency,
            'payment_method' => !empty($body['payment_method']) ? trim($body['payment_method']) : $payment->payment_method,
            'transaction_id' => array_key_exists('transaction_id', $body) ? (trim($body['transaction_id'] ?? null) ?: null) : $payment->transaction_id,
            'payment_date' => !empty($body['payment_date']) ? $body['payment_date'] : $payment->payment_date,
            'status' => $body['status'] ?? $payment->status,
            'notes' => array_key_exists('notes', $body) ? (trim($body['notes'] ?? null) ?: null) : $payment->notes,
        ]);

        return [
            'status' => 'success',
            'message' => 'Payment updated successfully!',
            'data' => $payment->fresh()->load(['client', 'vendor', 'orderItem.website'])
        ];
    }

    /**
     * Delete a payment.
     */
    public function destroy(int $id): array
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return [
            'status' => 'success',
            'message' => 'Payment record deleted successfully.'
        ];
    }
}
