<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Review; //
use App\Models\User; //
use App\Models\Product; //
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = Review::with(['user', 'product'])->latest()->paginate(10); //
        return response()->json($reviews);
    }

    /**
     * Store a newly created review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id', //
                'product_id' => 'required|exists:products,id', //
                'rating' => 'required|integer|min:1|max:5', //
                'comment' => 'nullable|string', //
            ]);

            // Ensure unique review per user and product
            $existingReview = Review::where('user_id', $validatedData['user_id'])
                                    ->where('product_id', $validatedData['product_id'])
                                    ->first();

            if ($existingReview) {
                throw ValidationException::withMessages([
                    'product_id' => ['You have already submitted a review for this product.'],
                ]);
            }

            $review = Review::create($validatedData); //

            return response()->json([
                'message' => 'Review created successfully.',
                'review' => $review->load(['user', 'product']) //
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the review.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified review.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        return response()->json($review->load(['user', 'product'])); //
    }

    /**
     * Update the specified review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'sometimes|required|exists:users,id', //
                'product_id' => 'sometimes|required|exists:products,id', //
                'rating' => 'sometimes|required|integer|min:1|max:5', //
                'comment' => 'nullable|string', //
            ]);

            // If user_id or product_id are changing, re-check uniqueness
            if ((isset($validatedData['user_id']) && $validatedData['user_id'] != $review->user_id) ||
                (isset($validatedData['product_id']) && $validatedData['product_id'] != $review->product_id)) {
                $newUserId = $validatedData['user_id'] ?? $review->user_id;
                $newProductId = $validatedData['product_id'] ?? $review->product_id;

                $existingReview = Review::where('user_id', $newUserId)
                                        ->where('product_id', $newProductId)
                                        ->where('id', '!=', $review->id)
                                        ->first();
                if ($existingReview) {
                    throw ValidationException::withMessages([
                        'product_id' => ['This user has already reviewed this product.'],
                    ]);
                }
            }

            $review->update($validatedData); //

            return response()->json([
                'message' => 'Review updated successfully.',
                'review' => $review->load(['user', 'product']) //
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the review.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified review from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        try {
            $review->delete(); //
            return response()->json(['message' => 'Review deleted successfully.'], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the review.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
