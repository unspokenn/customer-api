<?php

use Illuminate\Http\Request;

Route::middleware('auth:sanctum')->get('/auth', function (Request $request) {
    return $request->user();
});
