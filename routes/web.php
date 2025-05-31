<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\SpaController;

// Route::get('/', function () {
//     return view('welcome');
// });

// This is a "catch-all" route. It means any URL that hasn't been
// matched by other web routes above it will be handled by SpaController@index.
// This allows React Router to take over navigation on the client-side.
Route::get('/{any?}', [SpaController::class, 'index'])->where('any', '.*');



