<?php

namespace Modules\AdminManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\AdminManagement\Models\Expense;
use Modules\AdminManagement\Http\Requests\StoreExpenseRequest;
use Modules\AdminManagement\Http\Requests\UpdateExpenseRequest;
use Illuminate\Http\JsonResponse;

class ExpenseController extends Controller
{
    /**
     * Get all expenses.
     */
    public function index(): JsonResponse
    {
        $expenses = Expense::latest('expense_date')->get();

        return response()->json([
            'status' => 'success',
            'data' => $expenses,
        ]);
    }

    /**
     * Store a newly created expense.
     */
    public function store(StoreExpenseRequest $request): JsonResponse
    {
        $expense = Expense::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Expense recorded successfully!',
            'data' => $expense,
        ], 201);
    }

    /**
     * Show a specific expense.
     */
    public function show(int $id): JsonResponse
    {
        $expense = Expense::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $expense,
        ]);
    }

    /**
     * Update an expense.
     */
    public function update(UpdateExpenseRequest $request, int $id): JsonResponse
    {
        $expense = Expense::findOrFail($id);
        $expense->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Expense updated successfully!',
            'data' => $expense->fresh(),
        ]);
    }

    /**
     * Delete an expense.
     */
    public function destroy(int $id): JsonResponse
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Expense record deleted successfully!',
        ]);
    }
}
