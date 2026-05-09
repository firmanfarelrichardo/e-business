<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Services\ExpenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct(
        protected ExpenseService $expenseService
    ) {}

    // GET /expenses
    public function index(Request $request): JsonResponse
    {
        $filters  = $request->only(['start_date', 'end_date', 'batch_id']);
        $expenses = $this->expenseService->getAllExpenses($filters);

        return response()->json([
            'success' => true,
            'data'    => $expenses,
        ]);
    }

    // GET /expenses/{id}
    public function show(string $id): JsonResponse
    {
        $expense = $this->expenseService->getExpenseById($id);

        return response()->json([
            'success' => true,
            'data'    => $expense,
        ]);
    }

    // POST /expenses
    public function store(ExpenseRequest $request): JsonResponse
    {
        $expense = $this->expenseService->createExpense(
            $request->validated(),
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil dicatat',
            'data'    => $expense,
        ], 201);
    }

    // PUT /expenses/{id}
    public function update(ExpenseRequest $request, string $id): JsonResponse
    {
        $expense = $this->expenseService->updateExpense($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil diupdate',
            'data'    => $expense,
        ]);
    }

    // DELETE /expenses/{id}
    public function destroy(string $id): JsonResponse
    {
        $this->expenseService->deleteExpense($id);

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil dihapus',
        ]);
    }
}