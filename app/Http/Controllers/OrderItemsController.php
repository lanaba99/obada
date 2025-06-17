<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Order;    // Import the Order model
use App\Models\Product;  // Import the Product model
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the order items.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orderItems = OrderItem::with(['order', 'product'])->latest()->paginate(10);
        return response()->json($orderItems);
    }

    /**
     * Store a newly created order item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0.01', // Price at the time of order
            ]);

            // Ensure order and product exist
            Order::findOrFail($validatedData['order_id']);
            Product::findOrFail($validatedData['product_id']);

            $orderItem = OrderItem::create($validatedData);

            return response()->json([
                'message' => 'Order item created successfully.',
                'order_item' => $orderItem->load(['order', 'product'])
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the order item.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified order item.
     *
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    public function show(OrderItem $orderItem)
    {
        return response()->json($orderItem->load(['order', 'product']));
    }

    /**
     * Update the specified order item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderItem $orderItem)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'sometimes|required|exists:orders,id',
                'product_id' => 'sometimes|required|exists:products,id',
                'quantity' => 'sometimes|required|integer|min:1',
                'price' => 'sometimes|required|numeric|min:0.01',
            ]);

            // If order_id or product_id are being updated, ensure they exist
            if (isset($validatedData['order_id'])) {
                Order::findOrFail($validatedData['order_id']);
            }
            if (isset($validatedData['product_id'])) {
                Product::findOrFail($validatedData['product_id']);
            }

            $orderItem->update($validatedData);

            return response()->json([
                'message' => 'Order item updated successfully.',
                'order_item' => $orderItem->load(['order', 'product'])
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the order item.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified order item from storage.
     *
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderItem $orderItem)
    {
        try {
            $orderItem->delete();
            return response()->json(['message' => 'Order item deleted successfully.'], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the order item.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
