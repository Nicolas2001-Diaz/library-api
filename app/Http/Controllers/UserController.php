<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;

use App\Repositories\Contracts\UserRepositoryInterface;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(private UserRepositoryInterface $users) {}

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

        $paginator = $this->users->paginateFiltered($filters, $perPage);

        return $this->successPaginated(UserResource::collection($paginator), 'Users retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = $this->users->create($data);

        return $this->success(new UserResource($user), 'User created successfully.', 201);
    }

    /**
     * Display the specified resource.
     */
     public function show(int $id): JsonResponse
    {
        $user = $this->users->findById($id);

        return $this->success(new UserResource($user), 'User retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->users->findById($id);

        $data = $request->validated();

        if (array_key_exists('password', $data) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user = $this->users->update($id, $data);

        return $this->success(new UserResource($user), 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->users->delete($id);

        return $this->success(null, 'User deleted successfully.');
    }
}
