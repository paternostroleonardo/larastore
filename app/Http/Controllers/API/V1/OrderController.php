<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Requests\UpdateStatusOrderRequest;
use App\Http\Requests\Requests\StoreOrderRequest;
use App\Repositories\OrderRepositories;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends ApiController
{

    private $orderRepositories;

    public function __construct(OrderRepositories $orderRepositories)
    {
        $this->orderRepositories = $orderRepositories;
    }

    public function index(Request $filter): JsonResponse
    {
        try {
            if ($filter->relation == 'all') {
                $orders = $this->orderRepositories->all($filter);
                return $this->showAll($orders);
            }

            $orders = $this->orderRepositories->all();
            return $this->showAll($orders);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function show(int $order): JsonResponse
    {
        try {
            $order = $this->orderRepositories->get($order);

            return $this->showOne($order);
        } catch (\Throwable $error) {
            return $this->showNone($error);
        }
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $validateData = $request->validated();

            $order = new Order($validateData);
            $order = $this->orderRepositories->save($order);

            return $this->showOne($order);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function destroy(int $order): JsonResponse
    {
        try {
            $order = Order::findOrFail($order);
            $this->orderRepositories->delete($order);

            return $this->successResponse('order deleted successfully', 200);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function updateStatus(UpdateStatusOrderRequest $request, int $order): JsonResponse
    {
        try {
            $order = Order::findOrFail($order);
            $validateData = $request->validated();

            $order->fill($validateData);
            $order = $this->orderRepositories->updateStatus($request->status);

            return $this->showOne($order);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function ordersByStatus(int $order, Request $status): JsonResponse
    {
        try {
            $order = Order::findOrFail($order);
            $orders = $this->orderRepositories->status($order, $status);

            return $this->showAll($orders);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function ordersBuyerByCustomer(int $order): JsonResponse
    {
        try {
            $order = Order::findOrFail($order);
            $OrderPayedCustomers = $this->orderRepositories->ordersBuyerCustomers($order);

            return $this->showAll($OrderPayedCustomers);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function ordersSellBySeller(int $order): JsonResponse
    {
        try {
            $order = Order::findOrFail($order);
            $OrderSellSellers = $this->orderRepositories->ordersSellSellers($order);

            return $this->showAll($OrderSellSellers);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }
}
