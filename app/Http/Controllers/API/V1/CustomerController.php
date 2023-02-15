<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Requests\StoreCustomerRequest;
use App\Http\Requests\Requests\UpdateCustomerRequest;
use App\Repositories\CustomerRepositories;
use Illuminate\Http\JsonResponse;
use App\Models\Customer;

class CustomerController extends ApiController
{

    private $customerRepositories;

    public function __construct(CustomerRepositories $customerRepositories)
    {
        $this->customerRepositories = $customerRepositories;
    }

    public function index(): JsonResponse
    {
        try {
            $customers = $this->customerRepositories->all();

            return $this->showAll($customers);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $customer = $this->customerRepositories->get($id);

            return $this->showOne($customer);
        } catch (\Throwable $error) {
            return $this->showNone($error);
        }
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        try {
            $validateData = $request->validated();

            $customer = new Customer($validateData);
            $customer = $this->customerRepositories->save($customer);

            return $this->showOne($customer);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        try {
            $validateData = $request->validated();

            $customer->fill($validateData);
            $customer = $this->customerRepositories->update($customer);

            return $this->showOne($customer);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function destroy(Customer $customer): JsonResponse
    {
        try {
            $this->customerRepositories->delete($customer);

            return $this->successResponse('customer deleted successfully', 200);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }
}
