<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Requests\StoreProductRequest;
use App\Http\Requests\Requests\UpdateProductRequest;
use App\Repositories\ProductRepositories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Models\Product;

class ProductController extends ApiController
{
    private $productRepositories;

    public function __construct(ProductRepositories $customerRepositories)
    {
        $this->productRepositories = $customerRepositories;
    }

    /**
     * all products
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $products = $this->productRepositories->all();

            return $this->showAll($products);
        } catch (\Throwable $error) {
            Log::debug('list products failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }

    /**
     * show product
     * @param int $product
     * @return JsonResponse
     */
    public function show(int $product): JsonResponse
    {
        try {
            $product = $this->productRepositories->get($product);

            return $this->showOne($product);
        } catch (\Throwable $error) {
            Log::debug('show product failed' . $error->getMessage());
            return $this->showNone($error);
        }
    }

    /**
     * store new product
     * @param StoreProductRequest $request
     * @return JsonResponse
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $validateData = $request->validated();

            $product = new Product($validateData);
            $product = $this->productRepositories->save($product);

            $user = Auth::user()->id;
            Log::info('save product with exit' . $product->code_product . $user);

            return $this->showOne($product);
        } catch (\Throwable $error) {
            Log::debug('save product failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }

    /**
     * update product
     * @param UpdateProductReques $request
     * @return JsonResponse
     */
    public function update(UpdateProductRequest $request, int $product): JsonResponse
    {
        try {
            $product = Product::findOrFail($product);
            $validateData = $request->validated();

            $product->fill($validateData);
            $product = $this->productRepositories->update($product);

            $user = Auth::user()->id;
            Log::info('updated product with exit' . $product->code_product . $user);

            return $this->showOne($product);
        } catch (\Throwable $error) {
            Log::debug('update product failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }

    /**
     * delete product
     * @param int $product
     * @return JsonResponse
     */
    public function destroy(int $product): JsonResponse
    {
        try {
            $product = Product::findOrFail($product);
            $this->productRepositories->delete($product);

            $user = Auth::user()->id;
            Log::info('deleted product with exit' . $product->code_product . $user);

            return $this->successDelete($product, 'product');
        } catch (\Throwable $error) {
            Log::debug('delete product failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }
}
