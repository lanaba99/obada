<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CartItem; //
use App\Models\User; //
use App\Models\Product; //
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Display a listing of the cart items.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Typically, you'd only show cart items for the authenticated user:
        // $cartItems = auth()->user()->cartItems()->with('product')->latest()->paginate(10);
        $cartItems = CartItem::with(['user', 'product'])->latest()->paginate(10); //
        return response()->json($cartItems);
    }

    /**
     * Store a newly created cart item in storage.
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
                'quantity' => 'required|integer|min:1', //
            ]);

            // Check if cart item already exists for this user and product
            $cartItem = CartItem::where('user_id', $validatedData['user_id'])
                                ->where('product_id', $validatedData['product_id'])
                                ->first();

            if ($cartItem) {
                // If exists, update quantity
                $cartItem->quantity += $validatedData['quantity'];
                $cartItem->save();
                $message = 'Cart item quantity updated successfully.';
            } else {
                // Otherwise, create new
                $cartItem = CartItem::create($validatedData); //
                $message = 'Cart item added successfully.';
            }

            return response()->json([
                'message' => $message,
                'cart_item' => $cartItem->load(['user', 'product']) //
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while storing the cart item.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified cart item.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\Response
     */
    public function show(CartItem $cartItem)
    {
        return response()->json($cartItem->load(['user', 'product'])); //
    }

    /**
     * Update the specified cart item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CartItem $cartItem)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'sometimes|required|exists:users,id', //
                'product_id' => 'sometimes|required|exists:products,id', //
                'quantity' => 'sometimes|required|integer|min:1', //
            ]);

            $cartItem->update($validatedData); //

            return response()->json([
                'message' => 'Cart item updated successfully.',
                'cart_item' => $cartItem->load(['user', 'product']) //
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the cart item.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified cart item from storage.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(CartItem $cartItem)
    {
        try {
            $cartItem->delete(); //
            return response()->json(['message' => 'Cart item deleted successfully.'], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the cart item.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
