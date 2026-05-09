<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreBatchRequest
 *
 * Validates incoming data for batch stock creation.
 * Enforces that only owners and employees can add new stock batches.
 *
 * The supplier_name field tracks which supplier provided the stock,
 * enabling future procurement reports and supplier performance analysis.
 */
class StoreBatchRequest extends FormRequest
{
    /**
     * Determine if the current user is authorized to add stock.
     *
     * Only owners and employees may add inventory batches.
     * Members (customers) must not have write access to stock data.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['owner', 'employee']);
    }

    /**
     * Validation rules for batch creation.
     *
     * - product_brand_id: Must reference an existing variant (required)
     * - initial_stock: The number of units received (min 1)
     * - purchase_price: Cost per unit from supplier (min 0)
     * - supplier_name: Optional text field for supplier tracking
     * - batch_code: Auto-generated if not provided; must be unique
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'product_brand_id' => 'required|uuid|exists:product_brands,id',
            'initial_stock'    => 'required|integer|min:1',
            'purchase_price'   => 'required|numeric|min:0',
            'supplier_name'    => 'nullable|string|max:100',
            'batch_code'       => 'nullable|string|max:50|unique:batches,batch_code',
            'is_active'        => 'nullable|boolean',
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
            'product_brand_id.required' => 'Varian produk wajib dipilih.',
            'product_brand_id.exists'   => 'Varian produk tidak ditemukan.',
            'initial_stock.required'    => 'Jumlah stok awal wajib diisi.',
            'initial_stock.min'         => 'Jumlah stok awal minimal 1 unit.',
            'purchase_price.required'   => 'Harga beli (modal) wajib diisi.',
            'purchase_price.min'        => 'Harga beli tidak boleh negatif.',
            'supplier_name.max'         => 'Nama supplier maksimal 100 karakter.',
            'batch_code.unique'         => 'Kode batch sudah digunakan.',
        ];
    }
}
