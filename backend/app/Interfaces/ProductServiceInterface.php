<?php

namespace App\Interfaces;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductServiceInterface
{
    public function getAllProducts($search = null): Collection|array;

    public function storeProduct($validatedData);

    public function updateProduct(Product $product, $validatedData);

    public function deleteProduct(Product $product);
}
