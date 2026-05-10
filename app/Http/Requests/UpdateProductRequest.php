<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdateProductRequest
 *
 * Validates incoming data for product updates. Includes an additional
 * 'remove_attachments' field that allows the frontend to flag specific
 * existing images for deletion without affecting the rest.
 *
 * Uses 'sometimes' modifier on required fields so partial updates
 * via API (PATCH-style) remain possible while full-form submissions
 * from Blade still enforce completeness.
 */
class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the current user is authorized to update a product.
     *
     * Same role restriction as creation: members cannot modify the catalog.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role !== 'member';
    }

    /**
     * Validation rules for product updates.
     *
     * - remove_attachments: Array of storage paths to delete. Each path
     *   is validated as a string to prevent injection of non-path values.
     * - All other rules mirror StoreProductRequest with 'sometimes' prefix.
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
            'remove_attachments' => 'nullable|array',
            'remove_attachments.*' => 'string',
            'brand_id' => 'nullable|uuid|exists:brands,id',
            'brand_name' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:100',
            'selling_price' => 'nullable|numeric|min:0',
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
