<?php

namespace App\Interfaces;

use App\Models\Order;
use App\Models\Product;

interface OrderProductServiceInterface
{
    public function attachProductToOrder(Order $order, Product $product);
}

