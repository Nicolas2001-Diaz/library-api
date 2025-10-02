<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanRequest;
use App\Http\Resources\LoanResource;
use App\Models\Loan;
use App\Repositories\Contracts\LoanRepositoryInterface;
use App\Services\LoanService;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{
    public function __construct(
        private LoanRepositoryInterface $loans,
        private LoanService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'user_id' => $request->query('user_id'),
            'book_id' => $request->query('book_id'),
            'active'  => $request->has('active') ? filter_var($request->query('active'), FILTER_VALIDATE_BOOLEAN) : null,
        ];

        $perPage = (int) $request->query('per_page', 10);

        if ($perPage <= 0) $perPage = 10;
        if ($perPage > 100) $perPage = 100;

        $paginator = $this->loans->paginateFiltered($filters, $perPage);

        return $this->successPaginated(LoanResource::collection($paginator), 'Loans retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoanRequest $request): JsonResponse
    {
        $loan = $this->service->createLoan($request->validated());

        return $this->success(new LoanResource($loan), 'Loan created successfully.', 201);
    }

    /**
     * Display the specified resource.
     */
     public function show(int $id): JsonResponse
    {
        $loan = $this->loans->findById($id);

        return $this->success(new LoanResource($loan), 'Loan retrieved successfully.');
    }

    /**
     * Return the specified resource in storage.
     */
    public function return(int $id): JsonResponse
    {
        $loan = $this->loans->findById($id);

        $loan = $this->service->returnLoan($loan);
        
        return $this->success(new LoanResource($loan), 'Loan returned successfully.');
    }
}
