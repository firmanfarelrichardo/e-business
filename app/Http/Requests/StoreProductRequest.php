<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreProductRequest
 *
 * Validates incoming data for product creation. The authorize() method
 * enforces role-based access so that only owners and employees can
 * create products -- members are restricted.
 *
 * Field naming uses 'attachments' (not 'images') to align with both
 * the database column name and the Blade form field names, preventing
 * mapping confusion across the stack.
 */
class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the current user is authorized to create a product.
     *
     * Members (customers) should not have write access to the product
     * catalog. Returning false triggers a 403 Forbidden response
     * automatically via Laravel's exception handler.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role !== 'member';
    }

    /**
     * Validation rules for product creation.
     *
     * - name: Required, capped at 50 chars to match DB column constraint
     * - category_id: Must reference an existing category (UUID)
     * - description: Optional free text
     * - attachments: Optional array of image files, each max 4MB
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'category_id' => 'required|uuid|exists:product_categories,id',
            'description' => 'nullable|string|max:255',
            'attachments' => 'nullable|array|max:10',
            'attachments.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
            'brand_id' => 'nullable|uuid|exists:brands,id',
            'brand_name' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:100',
            'selling_price' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     *
     * Provides user-friendly feedback in the application's primary
     * language to maintain a consistent experience.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'name.max' => 'Nama produk maksimal 50 karakter.',
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'attachments.max' => 'Maksimal 10 gambar per produk.',
            'attachments.*.image' => 'File harus berupa gambar.',
            'attachments.*.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau WebP.',
            'attachments.*.max' => 'Ukuran gambar maksimal 4MB per file.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $brandName = trim($this->input('brand_name', ''));
            $brandId = $this->input('brand_id');

            if ($brandName !== '' || $brandId) {
                if (empty($this->input('unit'))) {
                    $validator->errors()->add('unit', 'Unit wajib diisi saat menambahkan varian brand.');
                }
                $sellingPrice = $this->input('selling_price');
                if (!isset($sellingPrice) || $sellingPrice === '') {
                    $validator->errors()->add('selling_price', 'Harga jual wajib diisi saat menambahkan varian brand.');
                }
            }
        });
    }
}
