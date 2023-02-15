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
            if ($filter == 'all') {
                $orders = $this->orderRepositories->all($filter);
                return $this->showAll($orders);
            }

            $orders = $this->orderRepositories->all();
            return $this->showAll($orders);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $order = $this->orderRepositories->get($id);

            return $this->showOne($order);
        } catch (\Throwable $error) {
            return $this->showNone($error);
        }
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $order = new Order($request->all());
            $order = $this->orderRepositories->save($order);

            return $this->showOne($order);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function destroy(Order $order): JsonResponse
    {
        try {
            $this->orderRepositories->delete($order);

            return $this->successResponse('order deleted successfully', 200);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function updateStatus(UpdateStatusOrderRequest $request, Order $order): JsonResponse
    {
        try {
            $order->fill($request->all());
            $order = $this->orderRepositories->updateStatus($request->status);

            return $this->showOne($order);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function ordersByStatus(Order $order, Request $status): JsonResponse
    {
        try {
            $orders = $this->orderRepositories->status($order, $status);

            return $this->showAll($orders);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function ordersBuyerByCustomer(Order $order): JsonResponse
    {
        try {
            $OrderPayedCustomers = $this->orderRepositories->ordersBuyerCustomers($order);

            return $this->showAll($OrderPayedCustomers);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }

    public function ordersSellBySeller(Order $order): JsonResponse
    {
        try {
            $OrderSellSellers = $this->orderRepositories->ordersSellSellers($order);

            return $this->showAll($OrderSellSellers);
        } catch (\Throwable $error) {
            return $this->errorResponse($error->getMessage());
        }
    }
}