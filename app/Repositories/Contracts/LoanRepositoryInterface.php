<?php

namespace App\Repositories\Contracts;

use App\Models\Loan;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LoanRepositoryInterface
{
    public function query();
    public function findById(int $id): Loan;
    public function create(array $data): Loan;
    public function update(int $id, array $data): Loan;

    public function paginateFiltered(array $filters, int $perPage = 10): LengthAwarePaginator;
}
