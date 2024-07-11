<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products()
    {
        Product::factory()->count(5)->create();
        $response = $this->getJson('/api/products');
        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }

    public function test_can_create_product()
    {
        $data = [
            'name' => 'Product 1',
            'price' => 100,
        ];
        $response = $this->postJson('/api/products', $data);
        $this->assertDatabaseHas('products', $data);
        $response->assertStatus(201);
    }

    public function test_can_show_product()
    {
        $product = Product::factory()->create();
        $response = $this->get("/api/products/{$product->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $product->name,
            'price' => $product->price,
        ]);
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create();
        $productData = [
            'name' => 'Updated Product',
            'price' => 150,
        ];

        $response = $this->putJson('/api/products/' . $product->id, $productData);
        $this->assertDatabaseHas('products', $productData);
        $response->assertStatus(200);
    }

    public function test_can_delete_product()
    {
        $product = Product::factory()->create();
        $response = $this->deleteJson('/api/products/' . $product->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
