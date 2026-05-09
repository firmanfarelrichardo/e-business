<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    // GET /users — daftar semua user (bisa filter by role, is_active, search)
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['role', 'is_active', 'search']);
        $users   = $this->userService->getAllUsers($filters);

        return response()->json([
            'success' => true,
            'data'    => $users,
        ]);
    }

    // GET /users/{id}
    public function show(string $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        return response()->json([
            'success' => true,
            'data'    => $user,
        ]);
    }

    // POST /users — buat employee baru (hanya owner)
    public function store(UserRequest $request): JsonResponse
    {
        $user = $this->userService->createEmployee(
            $request->validated(),
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil ditambahkan',
            'data'    => $user,
        ], 201);
    }

    // PUT /users/{id}
    public function update(UserRequest $request, string $id): JsonResponse
    {
        $user = $this->userService->updateUser($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate',
            'data'    => $user,
        ]);
    }

    // DELETE /users/{id}
    public function destroy(string $id): JsonResponse
    {
        $this->userService->deleteUser($id);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus',
        ]);
    }

    // PATCH /users/{id}/toggle-active — aktif/nonaktifkan user
    public function toggleActive(string $id): JsonResponse
    {
        $user = $this->userService->toggleUserActive($id);

        return response()->json([
            'success' => true,
            'message' => 'Status user berhasil diubah',
            'data'    => $user,
        ]);
    }
}