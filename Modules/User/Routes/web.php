<?php

Route::prefix('user')->group(function() {
    Route::get('/', 'UserController@index');
});
