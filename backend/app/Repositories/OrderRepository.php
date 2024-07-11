<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    /**
     * @var Order
     */
    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

}
