<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/ecommerce', function (Request $request) {
    return $request->user();
});
