<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Order;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_orders_index()
    {
        Order::factory()->count(3)->create();
        $response = $this->get('/api/orders');
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    #[Test]
    public function it_creates_a_new_order()
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

    #[Test]
    public function it_fails_to_create_an_order_when_validation_fails()
    {
        $data = [
            'name' => '', // name is required
            'description' => 'Test Description',
            'date' => '2024-07-06'
        ];
        $response = $this->postJson('/api/orders', $data);
        $response->assertStatus(422); // Unprocessable Entity
        $response->assertJsonValidationErrors('name');
    }

    #[Test]
    public function it_shows_an_order()
    {
        $order = Order::factory()->create();
        $response = $this->get("/api/orders/{$order->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $order->name,
            'description' => $order->description,
            'date' => Carbon::parse($order->date)->format('Y-m-d H:i:s'),
        ]);
    }

    #[Test]
    public function it_updates_an_order()
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

    #[Test]
    public function it_fails_to_update_an_order_when_validation_fails()
    {
        $order = Order::factory()->create();
        $updatedData = [
            'name' => '', // name is required
            'description' => 'Updated Description',
            'date' => '2024-07-07'
        ];
        $response = $this->putJson("/api/orders/{$order->id}", $updatedData);
        $response->assertStatus(422); // Unprocessable Entity
        $response->assertJsonValidationErrors('name');
    }

    #[Test]
    public function it_deletes_an_order()
    {
        $order = Order::factory()->create();
        $response = $this->delete("/api/orders/{$order->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
