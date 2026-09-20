<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Proves the JWT issued at login is independently verifiable, decoupled
// from the Laravel session used by the Inertia front-end.
Route::middleware('jwt.auth')->get('/me', function (Request $request) {
    return response()->json([
        'user' => $request->user(),
    ]);
});
