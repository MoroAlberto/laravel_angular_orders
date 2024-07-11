<?php

namespace App\Services;

use App\Interfaces\OrderProductServiceInterface;
use App\Models\Order;
use App\Models\Product;

class OrderProductService implements OrderProductServiceInterface
{
    public function attachProductToOrder(Order $order, Product $product)
    {
        if (!$order->exists) {
            throw new \InvalidArgumentException('Order not found');
        }

        if (!$product->exists) {
            throw new \InvalidArgumentException('Product not found');
        }

        if (!$order->products()->where('product_id', $product->id)->exists()) {
            $order->products()->attach($product);
        }
    }
}
