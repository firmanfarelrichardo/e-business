<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Services\OrderService;

/**
 * Web\OrderController
 *
 * Thin controller for the dashboard order queue management page.
 * Handles the admin-side queue view and status transitions.
 *
 * This controller is distinct from the root-level OrderController
 * which handles the customer-facing kasir/history/invoice pages.
 * The namespace separation (Web\) makes this distinction explicit.
 */
class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display the order queue management page.
     *
     * Reads the 'status' query parameter to filter orders and passes
     * the pending count for the notification badge on the tab header.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $statusFilter = request('status', 'pending');
        $orders       = $this->orderService->getActiveOrders($statusFilter, 20);
        $pendingCount = $this->orderService->getPendingCount();

        return view('dashboard.queues.index', compact('orders', 'pendingCount', 'statusFilter'));
    }

    /**
     * Update the status of a specific order.
     *
     * The UpdateOrderStatusRequest handles authorization (owner/employee only)
     * and validates the status value. The service layer enforces transition
     * rules and performs stock deduction when applicable.
     *
     * @param  \App\Http\Requests\UpdateOrderStatusRequest $request
     * @param  string $id  UUID of the order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(UpdateOrderStatusRequest $request, string $id)
    {
        try {
            $this->orderService->updateOrderStatus($id, $request->validated()['status']);
            return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
