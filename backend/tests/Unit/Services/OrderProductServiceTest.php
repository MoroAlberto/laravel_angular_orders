<?php

namespace Tests\Unit\Services;

use App\Models\Order;
use App\Models\Product;
use App\Services\OrderProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderProductServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OrderProductService $orderProductService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderProductService = app(OrderProductService::class);
    }

    public function test_attach_product_to_order_successfully()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();

        $this->orderProductService->attachProductToOrder($order, $product);

        $this->assertDatabaseHas('order_product', [
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_attach_product_to_order_that_does_not_exist()
    {
        $this->expectException(\InvalidArgumentException::class);

        $order = new Order(); // Create a new, non-existing order
        $product = Product::factory()->create();

        $this->orderProductService->attachProductToOrder($order, $product);
    }

    public function test_attach_non_existing_product_to_order()
    {
        $this->expectException(\InvalidArgumentException::class);

        $order = Order::factory()->create();
        $product = new Product(); // Create a new, non-existing product

        $this->orderProductService->attachProductToOrder($order, $product);
    }

    public function test_attach_existing_product_to_order_twice()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();

        $this->orderProductService->attachProductToOrder($order, $product);
        $this->orderProductService->attachProductToOrder($order, $product); // Try attaching the same product again

        $this->assertCount(1, $order->products);
    }
}
