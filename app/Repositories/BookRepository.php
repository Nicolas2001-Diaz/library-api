<?php

namespace App\Repositories;

use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookRepository implements BookRepositoryInterface
{
    public function __construct(protected Book $book) {}

    public function query()
    {
        return  $this->book->query();
    }

    public function findById(int $id): ?Book
    {
        return $this->book->findOrFail($id);
    }

    public function create(array $data): Book
    {
        return $this->book->create($data);
    }

    public function update(int $id, array $data): Book
    {
        $book =  $this->findById($id);

        $book->update($data);

        return $book;
    }

    public function delete(int $id): void
    {
        $book = $this->findById($id);

        $book->delete();
    }

    public function paginateFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $q = $this->query();

        if (!empty($filters['search'])) {
            $s = $filters['search'];

            $q->where(function ($qq) use ($s) {
                $qq->where('title', 'like', "%$s%")
                    ->orWhere('author', 'like', "%$s%")
                    ->orWhere('genre', 'like', "%$s%")
                    ->orWhere('stock', 'like', "%$s%");
            });
        }

        return $q->latest()->paginate($perPage);
    }
}
