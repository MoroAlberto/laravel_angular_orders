<?php

namespace Tests\Unit\Services;

use Illuminate\Support\Collection;
use Tests\TestCase;
use App\Services\ProductService;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = new ProductService();
    }

    public function test_it_can_get_all_products()
    {
        Product::factory()->count(5)->create();

        $products = $this->productService->getAllProducts(null);

        $this->assertInstanceOf(Collection::class, $products);
        $this->assertCount(5, $products);
    }

    public function test_it_can_filter_products_by_name()
    {
        Product::factory()->create(['name' => 'Product A']);
        Product::factory()->count(3)->create();

        $products = $this->productService->getAllProducts('Product A');

        $this->assertCount(1, $products);
        $this->assertEquals('Product A', $products->first()->name);
    }

    public function test_it_can_filter_products_by_price()
    {
        Product::factory()->create(['price' => 10.00]);
        Product::factory()->count(3)->create(['price' => 20.00]);

        $products = $this->productService->getAllProducts(20.00);

        $this->assertCount(3, $products);
        $products->each(function ($product) {
            $this->assertEquals(20.00, $product->price);
        });
    }


    public function test_it_can_create_a_product()
    {
        $data = [
            'name' => 'Test Product',
            'price' => 100
        ];

        $product = $this->productService->storeProduct($data);

        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_it_can_update_a_product()
    {
        $product = Product::factory()->create();

        $data = [
            'name' => 'Updated Product',
            'price' => 150
        ];

        $updatedProduct = $this->productService->updateProduct($product, $data);

        $this->assertEquals('Updated Product', $updatedProduct->name);
        $this->assertEquals(150, $updatedProduct->price);
    }

    public function test_it_can_delete_a_product()
    {
        $product = Product::factory()->create();

        $this->productService->deleteProduct($product);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
