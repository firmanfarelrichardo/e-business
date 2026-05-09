<?php

namespace App\Services;

use App\Models\ExpenseItem;
use App\Models\ProductBrand;
use App\Repositories\ExpenseRepository;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    public function __construct(
        protected ExpenseRepository $expenseRepository
    ) {
    }

    public function getAllExpenses(array $filters = [])
    {
        return $this->expenseRepository->getAll($filters);
    }

    public function getExpenseById(string $id)
    {
        return $this->expenseRepository->findById($id);
    }

    public function createExpense(array $data, string $createdBy)
    {
        return DB::transaction(function () use ($data, $createdBy) {
            // Hitung total dari semua item
            $total = collect($data['items'])->sum(function ($item) {
                return $item['quantity'] * $item['purchase_price'];
            });

            // Buat expense
            $expense = $this->expenseRepository->create([
                'total_amount' => $total,
                'note' => $data['note'] ?? null,
                'batch_id' => $data['batch_id'] ?? null,
                'created_by' => $createdBy,
                'created_at' => now(),
            ]);

            // Buat item-item expense
            $affectedProductBrands = [];
            foreach ($data['items'] as $item) {
                ExpenseItem::create([
                    'expense_id' => $expense->id,
                    'product_brand_id' => $item['product_brand_id'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'subtotal' => $item['quantity'] * $item['purchase_price'],
                    'created_at' => now(),
                ]);
                $affectedProductBrands[] = $item['product_brand_id'];
            }

            // Recalculate WAC (queue model) for each affected ProductBrand
            foreach (array_unique($affectedProductBrands) as $pbId) {
                ProductBrand::find($pbId)?->recalculateWAC();
            }

            return $expense->load('items.productBrand');
        });
    }

    public function updateExpense(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            // Hapus item lama, ganti dengan yang baru
            $expense = $this->expenseRepository->findById($id);
            $expense->items()->delete();

            $total = collect($data['items'])->sum(function ($item) {
                return $item['quantity'] * $item['purchase_price'];
            });

            $expense->update([
                'total_amount' => $total,
                'note' => $data['note'] ?? null,
                'batch_id' => $data['batch_id'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                ExpenseItem::create([
                    'expense_id' => $expense->id,
                    'product_brand_id' => $item['product_brand_id'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'subtotal' => $item['quantity'] * $item['purchase_price'],
                    'created_at' => now(),
                ]);
            }

            return $expense->load('items.productBrand');
        });
    }

    public function deleteExpense(string $id)
    {
        return $this->expenseRepository->delete($id);
    }
}