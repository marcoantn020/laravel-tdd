<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Repository\Contracts\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Mockery\Exception;

class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ){}

    public function index(): AnonymousResourceCollection
    {
        $users = $this->userRepository->paginate();
        return UserResource::collection(collect($users->items()))
            ->additional([
                'meta' => [
                    'total' => $users->total(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'first_page' => $users->firstPage(),
                    'per_page' => $users->perPage()
                ],
            ]);
    }

    public function store(CreateUserRequest $request): UserResource
    {
        $payload = $request->validated();
        $user = $this->userRepository->create($payload);
        return new UserResource($user);
    }

    public function show($id): UserResource
    {
        $user = $this->userRepository->find($id);
        return new UserResource($user);
    }

    public function update($id, UpdateUserRequest $request): UserResource
    {
        $payload = $request->validated();
        $user = $this->userRepository->update($id, $payload);
        return new UserResource($user);
    }

    public function destroy($id): \Illuminate\Http\Response
    {
        $this->userRepository->delete($id);
        return response()->noContent();
    }
}
