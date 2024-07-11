<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function getAllProducts($search = null): Collection|array
    {
        $query = Product::query();

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('price', 'like', '%' . $search . '%');
            });
        }

        return $query->get();
    }

    public function storeProduct($validatedData)
    {
        return Product::create($validatedData);
    }

    public function updateProduct(Product $product, $validatedData)
    {
        $product->update($validatedData);
        return $product;
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
    }
}