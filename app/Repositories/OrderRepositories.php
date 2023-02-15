<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Relations\Relation;

class OrderRepositories extends BaseRepository
{
    const RELATIONS = [
        'seller',
        'customer'
    ];

    public function __construct(Order $order, array $relations)
    {
        if ($relations == 'all') {
            parent::__construct($order, self::RELATIONS);
            return;
        }

        parent::__construct($order);
    }

    public function ordersBuyerCustomers()
    {
        $OrderPayedCustomers = $this->model->where('status', 'PAYED')->with(['customer'])->get();

        return $OrderPayedCustomers;
    }

    public function ordersSellSellers()
    {
        $OrderSellSellers = $this->model->where('status', 'PAYED')->with(['seller'])->get();

        return $OrderSellSellers;
    }

    public function updateStatus(int $status)
    {
        $order = $this->model->update(['status' => $status]);

        return $order;
    }
}
