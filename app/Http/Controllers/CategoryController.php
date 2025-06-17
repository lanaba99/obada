<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category; //
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str; // To generate slug

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('products')->latest()->paginate(10); //
        return response()->json($categories);
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name', //
                'slug' => 'nullable|string|max:255|unique:categories,slug', //
                'description' => 'nullable|string', //
            ]);

            // Auto-generate slug if not provided
            if (empty($validatedData['slug'])) {
                $validatedData['slug'] = Str::slug($validatedData['name']);
            }

            $category = Category::create($validatedData); //

            return response()->json([
                'message' => 'Category created successfully.',
                'category' => $category
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return response()->json($category->load('products')); //
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $category->id, //
                'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id, //
                'description' => 'nullable|string', //
            ]);

            // Auto-generate slug if name is updated and slug is not provided
            if (isset($validatedData['name']) && empty($validatedData['slug'])) {
                $validatedData['slug'] = Str::slug($validatedData['name']);
            }

            $category->update($validatedData); //

            return response()->json([
                'message' => 'Category updated successfully.',
                'category' => $category
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete(); //
            return response()->json(['message' => 'Category deleted successfully.'], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
