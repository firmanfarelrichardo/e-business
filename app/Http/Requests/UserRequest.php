<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'name'     => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($userId)],
            'email'    => ['required', 'email', 'max:50', Rule::unique('users', 'email')->ignore($userId)],
            'password' => [$this->isMethod('POST') ? 'required' : 'nullable', 'string', 'min:8'],
            'address'  => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Nama wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique'   => 'Username sudah digunakan',
            'email.required'    => 'Email wajib diisi',
            'email.unique'      => 'Email sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min'      => 'Password minimal 8 karakter',
        ];
    }
}