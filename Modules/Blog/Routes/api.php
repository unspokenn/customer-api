<?php

use Illuminate\Http\Request;

Route::middleware('auth:sanctum')->get('/blog', function (Request $request) {
    return $request->user();
});
