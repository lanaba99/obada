<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
| Routes in this file will automatically be prefixed with /api by
| the RouteServiceProvider you created.
|
*/

/**
 * Default Sanctum route to get the authenticated user.
 * This is useful for checking authentication status from your frontend.
 * Accessible via: GET /api/user (if authenticated with Sanctum token)
 */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * API Version 1 Routes
 * All routes defined within this group will be prefixed with /api/v1/
 * For example, a route for '/products' here will be accessible as '/api/v1/products'.
 */
Route::prefix('v1')->name('api.v1.')->group(function () {

    // --- Authentication Routes ---
    // Example: (You will need to create AuthController and its methods first)
    // use App\Http\Controllers\Api\V1\AuthController;
    // Route::post('/register', [AuthController::class, 'register'])->name('register');
    // Route::post('/login', [AuthController::class, 'login'])->name('login');

    // --- Authenticated Routes ---
    // Route::middleware('auth:sanctum')->group(function () {
        // Example:
        // Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // --- Product Routes ---
        // use App\Http\Controllers\Api\V1\ProductController;
        // Route::apiResource('products', ProductController::class);

        // --- Category Routes ---
        // use App\Http\Controllers\Api\V1\CategoryController;
        // Route::apiResource('categories', CategoryController::class);

        // Add other resources like Cart, Orders, UserProfile here
        // as you build them.
    // });


    // --- Public Routes (if any besides auth) ---
    // Example for a contact form:
    // use App\Http\Controllers\Api\V1\ContactFormController;
    // Route::post('/contact', [ContactFormController::class, 'store'])->name('contact.store');

});

// You can add routes for V2 in the future like this:
// Route::prefix('v2')->name('api.v2.')->group(function () {
//     // Your V2 routes
// });