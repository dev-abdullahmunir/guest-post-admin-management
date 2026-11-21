<?php

namespace Modules\AdminManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\AdminManagement\Models\Payment;
use Modules\AdminManagement\Http\Requests\StorePaymentRequest;
use Modules\AdminManagement\Http\Requests\UpdatePaymentRequest;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    /**
     * Get all payments with relationships.
     */
    public function index(): JsonResponse
    {
        $payments = Payment::with(['client', 'vendor', 'orderItem.website'])->latest('payment_date')->get();

        return response()->json([
            'status' => 'success',
            'data' => $payments,
        ]);
    }

    /**
     * Store a newly created payment.
     */
    public function store(StorePaymentRequest $request): JsonResponse
    {
        $payment = Payment::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Payment recorded successfully!',
            'data' => $payment->load(['client', 'vendor', 'orderItem.website']),
        ], 201);
    }

    /**
     * Show a specific payment.
     */
    public function show(int $id): JsonResponse
    {
        $payment = Payment::with(['client', 'vendor', 'orderItem.website'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $payment,
        ]);
    }

    /**
     * Update a payment.
     */
    public function update(UpdatePaymentRequest $request, int $id): JsonResponse
    {
        $payment = Payment::findOrFail($id);
        $payment->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Payment updated successfully!',
            'data' => $payment->fresh(['client', 'vendor', 'orderItem.website']),
        ]);
    }

    /**
     * Delete a payment.
     */
    public function destroy(int $id): JsonResponse
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Payment record deleted successfully!',
        ]);
    }
}
