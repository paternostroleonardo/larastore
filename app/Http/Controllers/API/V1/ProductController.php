<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Requests\StoreProductRequest;
use App\Http\Requests\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\ProductRepositories;
use Illuminate\Http\JsonResponse;

class ProductController extends ApiController
{
    private $productRepositories;

    public function __construct(ProductRepositories $customerRepositories)
    {
        $this->productRepositories = $customerRepositories;
    }

    public function index(): JsonResponse
    {
        try {
            $products = $this->productRepositories->all();

            return $this->showAll($products);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $product = $this->productRepositories->get($id);

            return $this->showOne($product);
        } catch (\Throwable $error) {
            return $this->showNone($error);
        }
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $validateData = $request->validated();

            $product = new Product($validateData);
            $product = $this->productRepositories->save($product);

            return $this->showOne($product);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        try {
            $validateData = $request->validated();

            $product->fill($validateData);
            $product = $this->productRepositories->update($product);

            return $this->showOne($product);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            $this->productRepositories->delete($product);

            return $this->successResponse('product deleted successfully', 200);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }
}
