<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Wishlist; //
use App\Models\User; //
use App\Models\Product; //
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WishlistController extends Controller
{
    /**
     * Display a listing of the wishlist items.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Typically, you'd only show wishlist items for the authenticated user:
        // $wishlistItems = auth()->user()->wishlists()->with('product')->latest()->paginate(10);
        $wishlistItems = Wishlist::with(['user', 'product'])->latest()->paginate(10); //
        return response()->json($wishlistItems);
    }

    /**
     * Store a newly created wishlist item in storage.
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
            ]);

            // Ensure a user can only wishlist a product once
            $existingWishlistItem = Wishlist::where('user_id', $validatedData['user_id'])
                                            ->where('product_id', $validatedData['product_id'])
                                            ->first();

            if ($existingWishlistItem) {
                return response()->json([
                    'message' => 'Product already in wishlist.',
                    'wishlist_item' => $existingWishlistItem->load(['user', 'product']) //
                ], 200); // 200 OK because it's not an error, but already exists
            }

            $wishlistItem = Wishlist::create($validatedData); //

            return response()->json([
                'message' => 'Product added to wishlist successfully.',
                'wishlist_item' => $wishlistItem->load(['user', 'product']) //
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while adding to wishlist.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified wishlist item.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function show(Wishlist $wishlist)
    {
        return response()->json($wishlist->load(['user', 'product'])); //
    }

    /**
     * Remove the specified wishlist item from storage.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wishlist $wishlist)
    {
        try {
            $wishlist->delete(); //
            return response()->json(['message' => 'Product removed from wishlist successfully.'], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while removing from wishlist.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
