<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAll(array $filters = [])
    {
        $query = User::query();

        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'ilike', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'ilike', '%' . $filters['search'] . '%')
                  ->orWhere('username', 'ilike', '%' . $filters['search'] . '%');
            });
        }

        return $query->latest()->paginate(10);
    }

    public function findById(string $id): ?User
    {
        return User::findOrFail($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(string $id, array $data): User
    {
        $user = $this->findById($id);
        $user->update($data);
        return $user;
    }

    public function delete(string $id): bool
    {
        $user = $this->findById($id);
        return $user->delete();
    }

    public function toggleActive(string $id): User
    {
        $user = $this->findById($id);
        $user->update(['is_active' => !$user->is_active]);
        return $user;
    }
}