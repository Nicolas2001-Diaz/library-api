<?php

namespace App\Services;

use App\Models\{Book, Loan, User};

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class StatsService
{
    private int $ttl = 300;

    public function kpi(Carbon $from, Carbon $to): array
    {
        return Cache::remember("stats:kpi:{$from->toDateString()}:{$to->toDateString()}", $this->ttl, function () use ($from, $to) {
            $totalBooks   = Book::count();
            $totalUsers   = User::count();
            $loansActive  = Loan::whereNull('returned_at')->count();
            $loansTotal   = Loan::whereBetween('created_at', [$from, $to])->count();
            $overdues     = Loan::whereNull('returned_at')->whereDate('due_date', '<', now()->toDateString())->count();

            return compact('totalBooks','totalUsers','loansActive','loansTotal','overdues');
        });
    }

    public function topBooks(Carbon $from, Carbon $to, int $limit = 10): array
    {
        return Cache::remember("stats:topBooks:{$from->toDateString()}:{$to->toDateString()}:{$limit}", $this->ttl, function () use ($from, $to, $limit) {
            return Loan::select('book_id', DB::raw('COUNT(*) as cnt'))
                ->whereBetween('created_at', [$from, $to])
                ->groupBy('book_id')
                ->orderByDesc('cnt')
                ->with('book:id,title,author,genre')
                ->limit($limit)
                ->get()
                ->map(fn($r) => [
                    'book_id' => $r->book_id,
                    'title'   => $r->book?->title,
                    'author'  => $r->book?->author,
                    'genre'   => $r->book?->genre,
                    'value'   => (int) $r->cnt,
                ])->values()->all();
        });
    }

    public function loansSeries(Carbon $from, Carbon $to): array
    {
        return Cache::remember("stats:loansSeries:{$from->toDateString()}:{$to->toDateString()}", $this->ttl, function () use ($from, $to) {
            $rows = Loan::select(DB::raw('DATE(created_at) as d'), DB::raw('COUNT(*) as c'))
                ->whereBetween('created_at', [$from, $to])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('d')
                ->pluck('c', 'd'); 

            $period = CarbonPeriod::create($from, '1 day', $to);
            $out = [];
            
            foreach ($period as $day) {
                $key = $day->toDateString();
                $out[] = ['date' => $key, 'count' => (int) ($rows[$key] ?? 0)];
            }

            return $out;
        });
    }

    public function genreDistribution(Carbon $from, Carbon $to): array
    {
        return Cache::remember("stats:genre:{$from->toDateString()}:{$to->toDateString()}", $this->ttl, function () use ($from, $to) {
            return Loan::join('books', 'books.id', '=', 'loans.book_id')
                ->whereBetween('loans.created_at', [$from, $to])
                ->groupBy('books.genre')
                ->orderByDesc(DB::raw('COUNT(*)'))
                ->pluck(DB::raw('COUNT(*) as cnt'), 'books.genre')
                ->map(fn($cnt, $genre) => ['label' => $genre ?? 'Unknown', 'value' => (int) $cnt])
                ->values()->all();
        });
    }

    public function topUsers(Carbon $from, Carbon $to, int $limit = 10): array
    {
        return Cache::remember("stats:topUsers:{$from->toDateString()}:{$to->toDateString()}:{$limit}", $this->ttl, function () use ($from, $to, $limit) {
            return Loan::select('user_id', DB::raw('COUNT(*) as cnt'))
                ->whereBetween('created_at', [$from, $to])
                ->groupBy('user_id')
                ->orderByDesc('cnt')
                ->with('user:id,name,email')
                ->limit($limit)
                ->get()
                ->map(fn($r) => [
                    'user_id' => $r->user_id,
                    'name'    => $r->user?->name,
                    'email'   => $r->user?->email,
                    'value'   => (int) $r->cnt,
                ])->values()->all();
        });
    }

    public function overdues(): array
    {
        return Cache::remember("stats:overdues:today", $this->ttl, function () {
            return Loan::whereNull('returned_at')
                ->whereDate('due_date', '<', now()->toDateString())
                ->with(['user:id,name,email', 'book:id,title'])
                ->orderBy('due_date')
                ->get()
                ->map(fn($l) => [
                    'id'        => $l->id,
                    'user'      => ['id' => $l->user?->id, 'name' => $l->user?->name],
                    'book'      => ['id' => $l->book?->id, 'title' => $l->book?->title],
                    'due_date'  => optional($l->due_date)?->toDateString(),
                    'days_late' => optional($l->due_date)?->diffInDays(now(), false) ?? 0,
                ])->values()->all();
        });
    }
}
