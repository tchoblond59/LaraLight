<?php
    Route::group(['middleware' => ['web']], function () {
        Route::post('/LaraLight/action/setLevel','Tchoblond59\LaraLight\Controllers\LaraLightController@setLevel');
        Route::post('/LaraLight/config/create','Tchoblond59\LaraLight\Controllers\LaraLightController@createConfig');
        Route::post('/LaraLight/config/createPeriod','Tchoblond59\LaraLight\Controllers\LaraLightController@createPeriod');
        Route::get('/LaraLight/widget/{id}','Tchoblond59\LaraLight\Controllers\LaraLightController@config');
        Route::get('/LaraLight/widget/period/{id}','Tchoblond59\LaraLight\Controllers\LaraLightController@period');
        Route::get('/LaraLight/widget/periodConfig/{id}','Tchoblond59\LaraLight\Controllers\LaraLightController@periodConfig');
        Route::post('/LaraLight/config/assignPeriod','Tchoblond59\LaraLight\Controllers\LaraLightController@assignPeriod');
        Route::post('/LaraLight/widget/configuration/{id}','Tchoblond59\LaraLight\Controllers\LaraLightController@postConfiguration');
        Route::post('/LaraLight/mode/update','Tchoblond59\LaraLight\Controllers\LaraLightController@updateMode');
});