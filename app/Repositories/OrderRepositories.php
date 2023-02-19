<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use App\Models\Order;

class OrderRepositories extends BaseRepository
{
    const RELATIONS = [
        'seller',
        'customer'
    ];

    public function __construct(Order $order, array $relations = null)
    {
        if ($relations == 'all') {
            parent::__construct($order, self::RELATIONS);
            return;
        }

        parent::__construct($order);
    }

    public function ordersBuyerCustomers(int $customer): Paginator
    {
        $OrderPayedCustomers = $this->model->where('status', 'PAYED')->where('customer_id', $customer)->with(['customer'])->latest()->simplePaginate(10);

        return $OrderPayedCustomers;
    }

    public function ordersSellSellers(int $seller): Paginator
    {
        $OrderSellSellers = $this->model->where('status', 'PAYED')->where('seller_id', $seller)->with(['seller'])->latest()->simplePaginate(10);

        return $OrderSellSellers;
    }

    public function updateStatus(Model $model): Model
    {
        $model->update();

        return $model;
    }
}
