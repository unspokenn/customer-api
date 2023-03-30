<?php

Route::prefix('auth')->group(function() {
    Route::get('/', 'AuthController@index');
});
