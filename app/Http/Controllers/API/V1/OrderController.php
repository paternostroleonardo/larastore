<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Requests\UpdateStatusOrderRequest;
use App\Http\Requests\Requests\StoreOrderRequest;
use App\Repositories\OrderRepositories;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends ApiController
{

    private $orderRepositories;

    public function __construct(OrderRepositories $orderRepositories)
    {
        $this->orderRepositories = $orderRepositories;
    }

    /**
     * all orders
     * @return JsonResponse
     */
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
            Log::debug('list orders failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }

    /**
     * show order
     * @param int $order
     * @return JsonResponse
     */
    public function show(int $order): JsonResponse
    {
        try {
            $order = $this->orderRepositories->get($order);

            return $this->showOne($order);
        } catch (\Throwable $error) {
            Log::debug('show order failed' . $error->getMessage());
            return $this->showNone($error);
        }
    }

    /**
     * store new order
     * @param StoreOrderRequest $request
     * @return JsonResponse
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $validateData = $request->validated();

            $order = new Order($validateData);
            $order = $this->orderRepositories->save($order);

            $user = Auth::user()->id;
            Log::info('save order with exit' . $order->code_order . $user);

            return $this->showOne($order);
        } catch (\Throwable $error) {
            Log::debug('saved order failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }

    /**
     * destroy order
     * @param int $order
     * @return JsonResponse
     */
    public function destroy(int $order): JsonResponse
    {
        try {
            $order = Order::findOrFail($order);
            $this->orderRepositories->delete($order);

            $user = Auth::user()->id;
            Log::info('deleted order with exit' . $order->code_order . $user);

            return $this->successDelete($order, 'order');
        } catch (\Throwable $error) {
            Log::debug('deleted order failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }

    /**
     * update order
     * @param UpdateStatusOrderRequest $request
     * @param int $order
     * @return JsonResponse
     */
    public function updateStatus(UpdateStatusOrderRequest $request, int $order)
    {
        try {
            $order = Order::findOrFail($order);
            $validateData = $request->validated();

            $order->fill($validateData);
            $order = $this->orderRepositories->updateStatus($order);

            return $this->showOne($order);
        } catch (\Throwable $error) {
            Log::debug('update status order failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }

    /**
     * get order by status
     * @param UpdateStatusOrderRequest $request
     * @return JsonResponse
     */
    public function ordersByStatus(UpdateStatusOrderRequest $request): JsonResponse
    {
        try {
            $orders = $this->orderRepositories->status($request->status);

            return $this->showAll($orders);
        } catch (\Throwable $error) {
            Log::debug('list order by status failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }

    /**
     * get order payed by customers
     * @param int $customer
     * @return JsonResponse
     */
    public function ordersBuyerByCustomer(int $customer): JsonResponse
    {
        try {
            $OrderPayedCustomers = $this->orderRepositories->ordersBuyerCustomers($customer);

            return $this->showAll($OrderPayedCustomers);
        } catch (\Throwable $error) {
            Log::debug('list order by customers failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }

    /**
     * get order payed by seller
     * @param int $seller
     * @return JsonResponse
     */
    public function ordersSellBySeller(int $seller): JsonResponse
    {
        try {
            $OrderSellSellers = $this->orderRepositories->ordersSellSellers($seller);

            return $this->showAll($OrderSellSellers);
        } catch (\Throwable $error) {
            Log::debug('list order by sellers failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }
}
