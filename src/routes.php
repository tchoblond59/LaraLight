<?php
    Route::group(['middleware' => ['web']], function () {
        Route::post('/LaraLight/action/setLevel','Tchoblond59\LaraLight\Controllers\LaraLightController@setLevel');
});