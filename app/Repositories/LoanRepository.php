<?php

namespace App\Repositories;

use App\Models\Loan;
use App\Repositories\Contracts\LoanRepositoryInterface;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LoanRepository implements LoanRepositoryInterface
{
    public function __construct(protected Loan $loan) {}

    public function query()
    {
        return $this->loan->query()->with(['user', 'book']);
    }

    public function findById(int $id): Loan
    {
        return $this->query()->findOrFail($id);
    }

    public function create(array $data): Loan
    {
        return $this->loan->create($data);
    }

    public function update(int $id, array $data): Loan
    {
        $loan =  $this->findById($id);

        $loan->update($data);

        return $loan;
    }

    public function paginateFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $q = $this->query();

        if (!empty($filters['user_id'])) $q->where('user_id', (int)$filters['user_id']);
        if (!empty($filters['book_id'])) $q->where('book_id', (int)$filters['book_id']);

        if (!is_null($filters['active'])) {
            if ($filters['active'] === true) {
                $q->whereNull('returned_at');   // activos
            } else {
                $q->whereNotNull('returned_at'); // devueltos
            }
        }

        return $q->latest()->paginate($perPage);
    }
}
