<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // -------------------------------------------------------
    // LOGIN
    // -------------------------------------------------------

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Cek apakah user aktif
            if (Auth::user()->status !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Hubungi administrator.',
                ])->onlyInput('email');
            }

            return $this->redirectByRole(Auth::user()->role);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // -------------------------------------------------------
    // REGISTER
    // -------------------------------------------------------

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['nullable', 'string', 'max:100'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'role'       => ['required', 'in:member,employee,owner'],
            'password'   => ['required', 'confirmed', Password::min(8)],
            'terms'      => ['accepted'],
        ], [
            'first_name.required' => 'Nama depan wajib diisi.',
            'email.required'      => 'Email wajib diisi.',
            'email.unique'        => 'Email sudah terdaftar.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'terms.accepted'      => 'Anda harus menyetujui syarat dan ketentuan.',
        ]);

        $user = User::create([
            'name'     => trim($validated['first_name'] . ' ' . ($validated['last_name'] ?? '')),
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'role'     => $validated['role'],
            'status'   => 'active',   // atau 'inactive' jika perlu approval dulu
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return $this->redirectByRole($user->role);
    }

    // -------------------------------------------------------
    // LOGOUT
    // -------------------------------------------------------

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // -------------------------------------------------------
    // HELPER: redirect berdasarkan role
    // -------------------------------------------------------

    private function redirectByRole(string $role)
    {
        return match ($role) {
            'owner', 'employee' => redirect()->route('dashboard'),
            'member'            => redirect()->route('dashboard'),
            default             => redirect()->route('dashboard'),
        };
    }
}