<?php

namespace App\Interfaces;

use App\Models\Order;
use Illuminate\Support\Collection;

interface OrderServiceInterface
{
    public function getAllOrders($search = null, $date = null): Collection|array;

    public function storeOrder($validatedData);

    public function updateOrder(Order $order, $validatedData);

    public function deleteOrder(Order $order);
}
