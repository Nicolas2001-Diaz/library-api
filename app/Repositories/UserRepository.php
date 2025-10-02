<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(protected User $user) {}

    public function query()
    {
        return  $this->user->query();
    }

    public function findById(int $id): ?User
    {
        return $this->user->findOrFail($id);
    }

    public function create(array $data): User
    {
        return $this->user->create($data);
    }

    public function update(int $id, array $data): User
    {
        $user =  $this->findById($id);

        $user->update($data);

        return $user;
    }

    public function delete(int $id): void
    {
        $user = $this->findById($id);

        $user->delete();
    }

    public function paginateFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $q = $this->query();

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            
            $q->where(function ($qq) use ($s) {
                $qq->where('name', 'like', "%{$s}%")
                   ->orWhere('email', 'like', "%{$s}%");
            });
        }

        return $q->latest()->paginate($perPage);
    }
}
