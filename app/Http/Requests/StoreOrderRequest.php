<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreOrderRequest
 *
 * Validates incoming data for order creation from the kasir (POS)
 * interface. Each order must contain at least one item, and each
 * item must specify either a product variant or a service.
 *
 * This request does not enforce authentication because the kasir
 * page may be operated by an employee on behalf of a walk-in customer.
 */
class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Order creation is allowed for any authenticated user.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Validation rules for order creation.
     *
     * - user_id: The customer receiving the order (required for tracking)
     * - items: At least one line item is required
     * - Each item needs either product_brand_id or service_id, plus quantity
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id'                    => 'required|uuid|exists:users,id',
            'employee_id'                => 'nullable|uuid|exists:users,id',
            'note'                       => 'nullable|string|max:500',
            'items'                      => 'required|array|min:1',
            'items.*.product_brand_id'   => 'nullable|uuid|exists:product_brands,id',
            'items.*.service_id'         => 'nullable|uuid|exists:services,id',
            'items.*.quantity'           => 'required|integer|min:1',
            'items.*.note'               => 'nullable|string|max:255',
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
            'user_id.required'     => 'ID pelanggan wajib diisi.',
            'user_id.exists'       => 'Pelanggan tidak ditemukan.',
            'items.required'       => 'Pesanan harus memiliki minimal satu item.',
            'items.min'            => 'Pesanan harus memiliki minimal satu item.',
            'items.*.quantity.required' => 'Jumlah item wajib diisi.',
            'items.*.quantity.min'      => 'Jumlah item minimal 1.',
        ];
    }
}
