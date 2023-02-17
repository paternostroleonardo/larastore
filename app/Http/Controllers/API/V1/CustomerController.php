<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Requests\StoreCustomerRequest;
use App\Http\Requests\Requests\UpdateCustomerRequest;
use App\Repositories\CustomerRepositories;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

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
            Log::debug('lists customers failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }

    public function show(int $customer): JsonResponse
    {
        try {
            $customer = $this->customerRepositories->get($customer);

            return $this->showOne($customer);
        } catch (\Throwable $error) {
            Log::debug('show customer failed' . $error->getMessage());
            return $this->showNone($error);
        }
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        try {
            $validateData = $request->validated();

            $customer = new Customer($validateData);
            $customer = $this->customerRepositories->save($customer);

            $user = Auth::user()->email;
            
            Log::info('save customer with exit' . $customer->id . '-' . $customer->email . 'by' . '-' .$user);

            return $this->showOne($customer);
        } catch (\Throwable $error) {
            Log::debug('save customer failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }

    public function update(UpdateCustomerRequest $request, $customer): JsonResponse
    {
        try {
            $customer = Customer::findOrFail($customer);
            $validateData = $request->validated();

            $customer->fill($validateData);
            $customer = $this->customerRepositories->update($customer);

            $user = Auth::user()->id;
            Log::info('update customer with exit' . $customer->id . '-' . $customer->email . $user);

            return $this->showOne($customer);
        } catch (\Throwable $error) {
            Log::debug('update customer failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }

    public function destroy(int $customer): JsonResponse
    {
        try {
            $customer = Customer::findOrFail($customer);
            $this->customerRepositories->delete($customer);

            $user = Auth::user()->id;
            Log::info('deleted customer with exit' . $customer->id . '-' . $customer->email . $user);

            return $this->successResponse('customer deleted successfully', 200);
        } catch (\Throwable $error) {
            Log::debug('deleted customer failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }
}
