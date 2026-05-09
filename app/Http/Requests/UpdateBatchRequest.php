<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['owner', 'employee']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'initial_stock'  => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'initial_stock'  => 'Stok Awal',
            'purchase_price' => 'Harga Beli Dasar',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'initial_stock.required'  => ':attribute wajib diisi.',
            'initial_stock.integer'   => ':attribute harus berupa angka bulat.',
            'initial_stock.min'       => ':attribute minimal 1.',
            'purchase_price.required' => ':attribute wajib diisi.',
            'purchase_price.numeric'  => ':attribute harus berupa angka.',
            'purchase_price.min'      => ':attribute tidak boleh kurang dari 0.',
        ];
    }
}
