<?php

namespace Modules\AdminManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Shared\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExpenseController extends Controller
{
    /**
     * Render the Expenses Blade view.
     */
    public function renderView($viewFactory)
    {
        try {
            $expenses = Expense::latest('expense_date')->get()->toArray();
        } catch (\Exception $e) {
            $expenses = [];
        }

        return $viewFactory->make('expenses.index', [
            'expenses' => $expenses,
        ]);
    }

    /**
     * Store a newly created expense.
     */
    public function store(array $body): array
    {
        if (empty(trim($body['title'] ?? '')) || empty(trim($body['category'] ?? '')) || !isset($body['amount']) || empty($body['expense_date']) || empty($body['payment_method'])) {
            http_response_code(422);
            return ['status' => 'error', 'message' => 'Title, category, amount, expense date, and payment method are required.'];
        }

        $expense = Expense::create([
            'title' => trim($body['title']),
            'category' => trim($body['category']),
            'amount' => (float)$body['amount'],
            'currency' => !empty($body['currency']) ? strtoupper(trim($body['currency'])) : 'USD',
            'expense_date' => $body['expense_date'],
            'payment_method' => trim($body['payment_method']),
            'description' => trim($body['description'] ?? null) ?: null,
        ]);

        return [
            'status' => 'success',
            'message' => 'Expense recorded successfully!',
            'data' => $expense
        ];
    }

    /**
     * Show a specific expense.
     */
    public function show(int $id): array
    {
        $expense = Expense::findOrFail($id);
        return [
            'status' => 'success',
            'data' => $expense
        ];
    }

    /**
     * Update an expense.
     */
    public function update(int $id, array $body): array
    {
        $expense = Expense::findOrFail($id);

        $expense->update([
            'title' => !empty($body['title']) ? trim($body['title']) : $expense->title,
            'category' => !empty($body['category']) ? trim($body['category']) : $expense->category,
            'amount' => isset($body['amount']) ? (float)$body['amount'] : $expense->amount,
            'currency' => !empty($body['currency']) ? strtoupper(trim($body['currency'])) : $expense->currency,
            'expense_date' => !empty($body['expense_date']) ? $body['expense_date'] : $expense->expense_date,
            'payment_method' => !empty($body['payment_method']) ? trim($body['payment_method']) : $expense->payment_method,
            'description' => array_key_exists('description', $body) ? (trim($body['description'] ?? null) ?: null) : $expense->description,
        ]);

        return [
            'status' => 'success',
            'message' => 'Expense updated successfully!',
            'data' => $expense->fresh()
        ];
    }

    /**
     * Delete an expense.
     */
    public function destroy(int $id): array
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return [
            'status' => 'success',
            'message' => 'Expense record deleted successfully.'
        ];
    }
}
