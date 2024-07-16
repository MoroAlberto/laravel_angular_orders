<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\OrderProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class OrderProductController extends Controller
{
    protected OrderProductService $orderProductService;

    public function __construct(OrderProductService $orderProductService)
    {
        $this->orderProductService = $orderProductService;
    }

    public function attachProductToOrder(Request $request, Order $order, Product $product): JsonResponse
    {
        try {
            $this->orderProductService->attachProductToOrder($order, $product);
            return response()->json(['message' => 'Product attached to order successfully'], 200);
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}

