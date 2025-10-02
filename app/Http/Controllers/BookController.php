<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;

use App\Repositories\Contracts\BookRepositoryInterface;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Books",
 *     description="Operaciones sobre libros"
 * )
 */
class BookController extends Controller
{
    public function __construct(private BookRepositoryInterface $books) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->string('search')->toString(),
        ];

        $perPage = (int) $request->query('per_page', 10);

        if ($perPage <= 0) $perPage = 10;
        if ($perPage > 100) $perPage = 100;

        $paginator = $this->books->paginateFiltered($filters, $perPage);

        return $this->successPaginated(BookResource::collection($paginator), 'Books retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = $this->books->create($request->validated());

        return $this->success(new BookResource($book), 'Book created successfully.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $book = $this->books->findById($id);

        return $this->success(new BookResource($book), 'Book retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, int $id): JsonResponse
    {
        $book = $this->books->update($id, $request->validated());

        return $this->success(new BookResource($book), 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->books->delete($id);

        return $this->success(null, 'Book deleted successfully.');
    }
}
