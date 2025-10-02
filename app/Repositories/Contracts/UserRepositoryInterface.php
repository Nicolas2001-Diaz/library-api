<?php

namespace App\Repositories\Contracts;

use App\Models\User;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function query();
    public function findById(int $id): ?User;
    public function create(array $data): User;
    public function update(int $id, array $data): User;
    public function delete(int $id): void;
    
    public function paginateFiltered(array $filters, int $perPage = 10): LengthAwarePaginator;
}