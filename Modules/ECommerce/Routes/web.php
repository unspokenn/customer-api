<?php

Route::prefix('ecommerce')->group(function() {
    Route::get('/', 'ECommerceController@index');
});
