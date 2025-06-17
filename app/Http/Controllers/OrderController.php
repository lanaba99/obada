<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order; //
use App\Models\User; //
use App\Models\PromoCode; //
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with(['user', 'orderItems', 'payment', 'promoCode'])->latest()->paginate(10); //
        return response()->json($orders);
    }

    /**
     * Store a newly created order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id', //
                'total_amount' => 'required|numeric|min:0', //
                'final_amount' => 'required|numeric|min:0', //
                'discount_amount' => 'nullable|numeric|min:0', //
                'promo_code_id' => 'nullable|exists:promo_codes,id', //
                'status' => 'required|string|in:pending_payment,processing,shipped,delivered,cancelled,payment_failed', //
                'shipping_address' => 'required|string', // Could be an ID to addresses table or serialized data
                'billing_address' => 'required|string', // Could be an ID to addresses table or serialized data
                // For order items, you might handle them in a separate endpoint or nested creation
                // 'order_items' => 'required|array',
                // 'order_items.*.product_id' => 'required|exists:products,id',
                // 'order_items.*.quantity' => 'required|integer|min:1',
                // 'order_items.*.price' => 'required|numeric|min:0.01',
            ]);

            $order = Order::create($validatedData); //

            // If order_items are part of the creation, you would handle them here:
            // if (isset($request->order_items)) {
            //     foreach ($request->order_items as $item) {
            //         $order->orderItems()->create($item);
            //     }
            // }

            return response()->json([
                'message' => 'Order created successfully.',
                'order' => $order->load(['user', 'orderItems', 'payment', 'promoCode']) //
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return response()->json($order->load(['user', 'orderItems', 'payment', 'promoCode'])); //
    }

    /**
     * Update the specified order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'sometimes|required|exists:users,id', //
                'total_amount' => 'sometimes|required|numeric|min:0', //
                'final_amount' => 'sometimes|required|numeric|min:0', //
                'discount_amount' => 'nullable|numeric|min:0', //
                'promo_code_id' => 'nullable|exists:promo_codes,id', //
                'status' => 'sometimes|required|string|in:pending_payment,processing,shipped,delivered,cancelled,payment_failed', //
                'shipping_address' => 'sometimes|required|string', //
                'billing_address' => 'sometimes|required|string', //
            ]);

            $order->update($validatedData); //

            return response()->json([
                'message' => 'Order updated successfully.',
                'order' => $order->load(['user', 'orderItems', 'payment', 'promoCode']) //
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified order from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        try {
            $order->delete(); //
            return response()->json(['message' => 'Order deleted successfully.'], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
