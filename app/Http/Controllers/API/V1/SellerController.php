<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Requests\Auth\RegisterRequest;
use App\Http\Requests\Requests\UpdateSellerRequest;
use App\Repositories\SellerRepositories;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class SellerController extends ApiController
{

    private $sellerRepositories;

    public function __construct(SellerRepositories $sellerRepositories)
    {
        $this->sellerRepositories = $sellerRepositories;
    }

    public function index(): JsonResponse
    {
        try {
            $sellers = $this->sellerRepositories->all();
            return $this->showAll($sellers);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $seller = $this->sellerRepositories->get($id);

            return $this->showOne($seller);
        } catch (\Throwable $error) {
            return $this->showNone($error);
        }
    }

    public function store(RegisterRequest $request): JsonResponse
    {
        try {
            $validateData = $request->validated();

            $user = new User($validateData);
            $seller = $this->sellerRepositories->save($user);
            $seller->assignRole(User::ROLES['seller']);

            return $this->showOne($seller);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function update(UpdateSellerRequest $request, User $user): JsonResponse
    {
        try {
            $validateData = $request->validated();

            $user->fill($validateData);
            $seller = $this->sellerRepositories->update($user);

            return $this->showOne($seller);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function destroy(User $user): JsonResponse
    {
        try {
            $this->sellerRepositories->delete($user);

            return $this->successResponse('seller deleted successfully', 200);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }
}
