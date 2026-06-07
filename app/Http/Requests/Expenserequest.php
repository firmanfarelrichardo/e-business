<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'note'                          => ['nullable', 'string'],
            'batch_id'                      => ['nullable', 'uuid', 'exists:batches,id'],
            'items'                         => ['required', 'array', 'min:1'],
            'items.*.product_brand_id'      => ['required', 'uuid', 'exists:product_brands,id'],
            'items.*.quantity'              => ['required', 'integer', 'min:1'],
            'items.*.purchase_price'        => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'                     => 'Item pengeluaran wajib diisi',
            'items.*.product_brand_id.required'  => 'Produk wajib dipilih',
            'items.*.product_brand_id.exists'    => 'Produk tidak ditemukan',
            'items.*.quantity.required'          => 'Jumlah wajib diisi',
            'items.*.quantity.min'               => 'Jumlah minimal 1',
            'items.*.purchase_price.required'    => 'Harga beli wajib diisi',
        ];
    }
}