<?php

namespace App\Repositories\Contracts;

use App\Models\Book;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BookRepositoryInterface
{
    public function query();
    public function findById(int $id): ?Book;
    public function create(array $data): Book;
    public function update(int $id, array $data): Book;
    public function delete(int $id): void;
    
    public function paginateFiltered(array $filters, int $perPage = 10): LengthAwarePaginator;
}