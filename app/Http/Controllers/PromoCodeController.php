<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PromoCode; //
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PromoCodeController extends Controller
{
    /**
     * Display a listing of the promo codes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promoCodes = PromoCode::with('orders')->latest()->paginate(10); //
        return response()->json($promoCodes);
    }

    /**
     * Store a newly created promo code in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'code' => 'required|string|max:255|unique:promo_codes,code', //
                'type' => 'required|string|in:fixed,percentage', //
                'value' => 'required|numeric|min:0', //
                'min_order_amount' => 'nullable|numeric|min:0', //
                'expires_at' => 'nullable|date', //
                'usage_limit' => 'nullable|integer|min:1', //
                'used_count' => 'integer|min:0', //
                'is_active' => 'boolean', //
            ]);

            $promoCode = PromoCode::create($validatedData); //

            return response()->json([
                'message' => 'Promo code created successfully.',
                'promo_code' => $promoCode
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the promo code.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified promo code.
     *
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function show(PromoCode $promoCode)
    {
        return response()->json($promoCode->load('orders')); //
    }

    /**
     * Update the specified promo code in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PromoCode $promoCode)
    {
        try {
            $validatedData = $request->validate([
                'code' => 'sometimes|required|string|max:255|unique:promo_codes,code,' . $promoCode->id, //
                'type' => 'sometimes|required|string|in:fixed,percentage', //
                'value' => 'sometimes|required|numeric|min:0', //
                'min_order_amount' => 'nullable|numeric|min:0', //
                'expires_at' => 'nullable|date', //
                'usage_limit' => 'nullable|integer|min:1', //
                'used_count' => 'sometimes|integer|min:0', //
                'is_active' => 'sometimes|boolean', //
            ]);

            $promoCode->update($validatedData); //

            return response()->json([
                'message' => 'Promo code updated successfully.',
                'promo_code' => $promoCode->load('orders') //
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the promo code.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified promo code from storage.
     *
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(PromoCode $promoCode)
    {
        try {
            $promoCode->delete(); //
            return response()->json(['message' => 'Promo code deleted successfully.'], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the promo code.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
