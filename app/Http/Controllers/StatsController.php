<?php

namespace App\Http\Controllers;

use App\Http\Requests\RangeStatsRequest;

use App\Services\StatsService;

use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function __construct(private StatsService $stats) {}

    public function kpi(RangeStatsRequest $request): JsonResponse
    {
        [$from, $to] = $request->range();

        $data = $this->stats->kpi($from, $to);

        return $this->success($data, 'KPI retrieved successfully.');
    }

    public function topBooks(RangeStatsRequest $request): JsonResponse
    {
        [$from, $to, $limit] = $request->range();

        $data = $this->stats->topBooks($from, $to, $limit);

        return $this->success($data, 'Top books retrieved successfully.');
    }

    public function loansSeries(RangeStatsRequest $request): JsonResponse
    {
        [$from, $to] = $request->range();

        $data = $this->stats->loansSeries($from, $to);

        return $this->success($data, 'Loans series retrieved successfully.');
    }

    public function genreDistribution(RangeStatsRequest $request): JsonResponse
    {
        [$from, $to] = $request->range();

        $data = $this->stats->genreDistribution($from, $to);

        return $this->success($data, 'Genre distribution retrieved successfully.');
    }

    public function topUsers(RangeStatsRequest $request): JsonResponse
    {
        [$from, $to, $limit] = $request->range();

        $data = $this->stats->topUsers($from, $to, $limit);

        return $this->success($data, 'Top users retrieved successfully.');
    }

    public function overdues(): JsonResponse
    {
        $data = $this->stats->overdues();

        return $this->success($data, 'Overdues retrieved successfully.');
    }
}