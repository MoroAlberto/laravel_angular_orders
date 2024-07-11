<?php

namespace Tests\Feature\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_orders_index()
    {
        Order::factory()->count(3)->create();
        $response = $this->get('/api/orders');
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_it_create_order()
    {
        $data = [
            'name' => 'Test Order',
            'description' => 'Test Description',
            'date' => '2024-07-06'
        ];
        $response = $this->postJson('/api/orders', $data);
        $this->assertDatabaseHas('orders', $data);
        $response->assertStatus(201);
    }

    public function test_can_show_order()
    {
        $order = Order::factory()->create();
        $response = $this->get("/api/orders/{$order->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $order->name,
            'description' => $order->description,
            'date' => Carbon::parse($order->date)->format('Y-m-d'),
        ]);
    }

    public function test_can_update_order()
    {
        $order = Order::factory()->create();
        $updatedData = [
            'name' => 'Updated Name',
            'description' => 'Updated Description',
            'date' => '2024-07-07'
        ];
        $response = $this->putJson("/api/orders/{$order->id}", $updatedData);
        $this->assertDatabaseHas('orders', $updatedData);
        $response->assertStatus(200);
    }

    public function test_can_delete_order()
    {
        $order = Order::factory()->create();
        $response = $this->delete("/api/orders/{$order->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
