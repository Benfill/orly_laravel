<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductService
{
    /**
     * Get all products optionally filtered by category
     */
    public function getAllProducts(?int $categoryId = null): Collection
    {
        $query = Product::query();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query->get();
    }

    /**
     * Get a single product by ID
     */
    public function getProductById(int $id): ?Product
    {
        return Product::find($id);
    }

    /**
     * Create a new product
     */
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Update a product
     */
    public function updateProduct(int $id, array $data): ?Product
    {
        $product = Product::find($id);
        if ($product) {
            $product->update($data);
        }
        return $product;
    }

    /**
     * Delete a product
     */
    public function deleteProduct(int $id): bool
    {
        $product = Product::find($id);
        return $product ? $product->delete() : false;
    }
}
