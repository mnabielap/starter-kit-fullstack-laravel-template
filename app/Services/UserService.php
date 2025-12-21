<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    /**
     * Create a new user.
     */
    public function createUser(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return User::create($data);
    }

    /**
     * Update a user.
     */
    public function updateUser(User $user, array $data): User
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return $user;
    }

    /**
     * Delete a user.
     */
    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Query Users with Filters (Search, Role, Sort).
     */
    public function queryUsers(array $filters): LengthAwarePaginator
    {
        $query = User::query();

        // 1. Strict Filters (Role)
        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        // 2. Search Logic (Scope-aware)
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $scope = $filters['scope'] ?? 'all'; // Default to 'all' if not provided

            $query->where(function($q) use ($search, $scope) {
                if ($scope === 'name') {
                    $q->where('name', 'like', "%{$search}%");
                } elseif ($scope === 'email') {
                    $q->where('email', 'like', "%{$search}%");
                } elseif ($scope === 'id') {
                    $q->where('id', 'like', "%{$search}%");
                } else {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('id', 'like', "%{$search}%");
                }
            });
        }

        // 3. Sorting
        if (!empty($filters['sortBy'])) {
            $parts = explode(':', $filters['sortBy']);
            $field = $parts[0];
            $direction = isset($parts[1]) && strtolower($parts[1]) === 'asc' ? 'asc' : 'desc';
            
            if (in_array($field, ['name', 'email', 'created_at', 'role', 'id'])) {
                $query->orderBy($field, $direction);
            } else {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // 4. Pagination
        $limit = isset($filters['limit']) ? (int) $filters['limit'] : 10;
        
        return $query->paginate($limit);
    }
}