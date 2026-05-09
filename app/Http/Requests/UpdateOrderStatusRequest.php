<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdateOrderStatusRequest
 *
 * Validates status transition requests from the dashboard queue
 * management view. Restricts the allowed status values to prevent
 * invalid transitions at the request level, with deeper business
 * logic validation handled by the OrderService.
 */
class UpdateOrderStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to update order status.
     *
     * Only owners and employees can manage order queues.
     * Members (customers) should not have access to status controls.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['owner', 'employee']);
    }

    /**
     * Validation rules for status updates.
     *
     * The 'in' rule provides a first layer of defense against invalid
     * values. The OrderService's validateStatusTransition() method
     * provides the second layer by checking transition legality
     * based on the current order state.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'status' => 'required|string|in:processing,completed,cancelled',
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Status pesanan wajib dipilih.',
            'status.in'       => 'Status yang dipilih tidak valid. Pilihan: processing, completed, cancelled.',
        ];
    }
}
