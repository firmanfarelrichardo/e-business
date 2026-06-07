<?php
// ─────────────────────────────────────────────────────────────────────────────
// File: app/Http/Controllers/HistoryController.php
// ─────────────────────────────────────────────────────────────────────────────

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items'])
            ->latest();

        if ($request->filled('search')) {
            $query->whereHas('customer', fn($q) =>
                $q->where('name', 'ilike', '%' . $request->search . '%')
            );
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('payment')) {
            $query->where('payment_method', $request->payment);
        }

        $orders = $query->paginate(20);

        $summary = [
            'total_count'       => $query->count(),
            'total_revenue'     => Order::sum('total'),
            'avg_order'         => Order::avg('total') ?? 0,
            'unique_customers'  => Order::whereNotNull('user_id')->distinct('user_id')->count('user_id'),
        ];

        return view('history.index', compact('orders', 'summary'));
    }
}