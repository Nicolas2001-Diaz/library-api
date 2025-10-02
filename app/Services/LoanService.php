<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Loan;

use App\Repositories\Contracts\LoanRepositoryInterface;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

use Carbon\Carbon;

class LoanService
{
    public function __construct(private LoanRepositoryInterface $loans) {}

    public function createLoan(array $payload): Loan
    {
        $userId   = (int) $payload['user_id'];
        $bookId   = (int) $payload['book_id'];
        $quantity = max(1, (int) ($payload['quantity'] ?? 1));
        $dueDate  = !empty($payload['due_date']) ? Carbon::parse($payload['due_date']) : Carbon::now()->addDays(14);

        return DB::transaction(function () use ($userId, $bookId, $quantity, $dueDate, $payload) {
            /** @var Book $book */
            $book = Book::lockForUpdate()->findOrFail($bookId);

            if ($book->stock < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => ["Not enough stock. Available: {$book->stock}"],
                ]);
            }

            // Resta stock
            $book->decrement('stock', $quantity);

            // Crea el préstamo
            $loan = $this->loans->create([
                'user_id'   => $userId,
                'book_id'   => $book->id,
                'quantity'  => $quantity,
                'loan_date' => $payload['loan_date'] ?? now()->toDateString(),
                'due_date'  => $dueDate->toDateString(),
            ]);

            return $loan->load(['user','book']);
        });
    }

    public function returnLoan(Loan $loan): Loan
    {
        if ($loan->returned_at) {
            return $loan;
        }

        return DB::transaction(function () use ($loan) {
            /** @var Book $book */
            $book = Book::lockForUpdate()->findOrFail($loan->book_id);

            // Marca devolución
            $loan->update(['returned_at' => now()]);

            // Restituye stock
            $book->increment('stock', $loan->quantity);

            return $loan->load(['user','book']);
        });
    }
}
