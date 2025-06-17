<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User; // Import the User model
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AddressController extends Controller
{
    /**
     * Display a listing of the addresses.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // For security, typically you'd only show addresses for the authenticated user
        // Or, if for admin, show all
        $addresses = Address::with('user')->latest()->paginate(10);
        return response()->json($addresses);
    }

    /**
     * Store a newly created address in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'address_line1' => 'required|string|max:255',
                'address_line2' => 'nullable|string|max:255',
                'city' => 'required|string|max:100',
                'state' => 'nullable|string|max:100',
                'postal_code' => 'required|string|max:20',
                'country' => 'required|string|max:100',
                'phone_number' => 'nullable|string|max:20',
                'is_default' => 'boolean',
            ]);

            // Optional: If 'is_default' is true, set other addresses for this user to false
            if (isset($validatedData['is_default']) && $validatedData['is_default']) {
                Address::where('user_id', $validatedData['user_id'])->update(['is_default' => false]);
            }

            $address = Address::create($validatedData);

            return response()->json([
                'message' => 'Address created successfully.',
                'address' => $address->load('user')
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the address.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified address.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        return response()->json($address->load('user'));
    }

    /**
     * Update the specified address in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Address $address)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'sometimes|required|exists:users,id',
                'address_line1' => 'sometimes|required|string|max:255',
                'address_line2' => 'nullable|string|max:255',
                'city' => 'sometimes|required|string|max:100',
                'state' => 'nullable|string|max:100',
                'postal_code' => 'sometimes|required|string|max:20',
                'country' => 'sometimes|required|string|max:100',
                'phone_number' => 'nullable|string|max:20',
                'is_default' => 'sometimes|boolean',
            ]);

            // Optional: If 'is_default' is true, set other addresses for this user to false
            if (isset($validatedData['is_default']) && $validatedData['is_default']) {
                $userId = isset($validatedData['user_id']) ? $validatedData['user_id'] : $address->user_id;
                Address::where('user_id', $userId)->where('id', '!=', $address->id)->update(['is_default' => false]);
            }

            $address->update($validatedData);

            return response()->json([
                'message' => 'Address updated successfully.',
                'address' => $address->load('user')
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the address.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified address from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        try {
            $address->delete();
            return response()->json(['message' => 'Address deleted successfully.'], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the address.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
