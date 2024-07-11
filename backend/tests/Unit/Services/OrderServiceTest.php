<?php

namespace Tests\Unit\Services;

use Illuminate\Support\Collection;
use Tests\TestCase;
use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OrderService $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = new OrderService();
    }

    public function test_it_can_get_all_orders()
    {
        Order::factory()->count(5)->create();
        $orders = $this->orderService->getAllOrders(null, null);

        $this->assertInstanceOf(Collection::class, $orders);
        $this->assertCount(5, $orders);
    }

    public function test_it_can_filter_orders_by_search()
    {
        Order::factory()->create(['name' => 'Specific Order']);
        Order::factory()->count(9)->create();

        $orders = $this->orderService->getAllOrders('Specific Order', null);

        $this->assertCount(1, $orders);
        $this->assertEquals('Specific Order', $orders->first()->name);
    }

    public function test_it_can_filter_orders_by_date()
    {
        $specificDate = '2024-07-10';
        Order::factory()->create(['date' => $specificDate]);
        $orders = $this->orderService->getAllOrders(null, $specificDate);

        $this->assertCount(1, $orders);
        $this->assertEquals($specificDate, $orders->first()->date->format('Y-m-d'));
    }

    public function test_it_can_filter_orders_by_search_and_date()
    {
        $specificDate = '2024-07-10';
        Order::factory()->create(['name' => 'Specific Order', 'date' => $specificDate]);
        Order::factory()->count(9)->create();

        $orders = $this->orderService->getAllOrders('Specific Order', $specificDate);

        $this->assertCount(1, $orders);
        $this->assertEquals('Specific Order', $orders->first()->name);
        $this->assertEquals($specificDate, $orders->first()->date->format('Y-m-d'));
    }


    public function test_it_can_create_an_order()
    {
        $data = [
            'name' => 'Test Order',
            'description' => 'Test Description',
            'date' => '2024-07-06'
        ];
        $order = $this->orderService->storeOrder($data);
        $this->assertDatabaseHas('orders', $data);
    }

    public function test_it_can_update_an_order()
    {
        $order = Order::factory()->create();
        $data = [
            'name' => 'Updated Order',
            'description' => 'Updated Description',
            'date' => '2024-07-07'
        ];

        $updatedOrder = $this->orderService->updateOrder($order, $data);

        $this->assertEquals('Updated Order', $updatedOrder->name);
        $this->assertEquals('Updated Description', $updatedOrder->description);
    }

    public function test_it_can_delete_an_order()
    {
        $order = Order::factory()->create();
        $this->orderService->deleteOrder($order);
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
