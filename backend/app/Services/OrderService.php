<?php

namespace App\Services;

use App\Interfaces\OrderServiceInterface;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class OrderService implements OrderServiceInterface
{
    public function getAllOrders($search = null, $date = null): Collection|array
    {
        $query = Order::with('products');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($date) {
            $query->whereDate('date', $date);
        }

        return $query->get();
    }

    public function storeOrder($validatedData)
    {
        return Order::create($validatedData);
    }

    public function updateOrder(Order $order, $validatedData)
    {
        $order->update($validatedData);
        return $order;
    }

    public function deleteOrder(Order $order)
    {
        $order->delete();
    }
}
