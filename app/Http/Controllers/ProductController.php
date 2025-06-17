<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product; //
use App\Models\Category; //
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::with(['categories', 'reviews', 'cartItems', 'wishlists', 'orderItems']) //
                            ->latest()
                            ->paginate(10);
        return response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255', //
                'description' => 'nullable|string', //
                'price' => 'required|numeric|min:0.01', //
                'image_url' => 'nullable|url|max:255', //
                'stock' => 'required|integer|min:0', //
                'is_featured' => 'boolean', //
                'category_ids' => 'nullable|array', // For attaching categories
                'category_ids.*' => 'exists:categories,id',
            ]);

            $product = Product::create($validatedData); //

            // Attach categories
            if (isset($validatedData['category_ids'])) {
                $product->categories()->sync($validatedData['category_ids']);
            }

            return response()->json([
                'message' => 'Product created successfully.',
                'product' => $product->load('categories') //
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return response()->json($product->load(['categories', 'reviews', 'cartItems', 'wishlists', 'orderItems'])); //
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255', //
                'description' => 'nullable|string', //
                'price' => 'sometimes|required|numeric|min:0.01', //
                'image_url' => 'nullable|url|max:255', //
                'stock' => 'sometimes|required|integer|min:0', //
                'is_featured' => 'sometimes|boolean', //
                'category_ids' => 'nullable|array',
                'category_ids.*' => 'exists:categories,id',
            ]);

            $product->update($validatedData); //

            // Sync categories if provided
            if (isset($validatedData['category_ids'])) {
                $product->categories()->sync($validatedData['category_ids']);
            }

            return response()->json([
                'message' => 'Product updated successfully.',
                'product' => $product->load('categories') //
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete(); //
            return response()->json(['message' => 'Product deleted successfully.'], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
