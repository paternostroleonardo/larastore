<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Requests\Auth\RegisterRequest;
use App\Http\Requests\Requests\UpdateSellerRequest;
use App\Repositories\SellerRepositories;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

    public function show(int $seller): JsonResponse
    {
        try {
            $seller = $this->sellerRepositories->get($seller);

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
            Log::debug('stored seller failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }

    public function update(UpdateSellerRequest $request, int $seller): JsonResponse
    {
        try {
            $seller = User::findOrFail($seller);
            $validateData = $request->validated();

            $seller->fill($validateData);
            $seller = $this->sellerRepositories->update($seller);

            return $this->showOne($seller);
        } catch (\Throwable $error) {
            Log::debug('updated seller failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }

    public function destroy(int $seller): JsonResponse
    {
        try {
            $seller = User::findOrFail($seller);
            $this->sellerRepositories->delete($seller);

            return $this->successResponse('seller deleted successfully', 200);
        } catch (\Throwable $error) {
            Log::debug('deleted seller failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }
}
