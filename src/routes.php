<?php

use Illuminate\Support\Facades\Route;

    Route::group(['middleware' => ['web', 'auth']], function () {
        Route::post('/LaraLight/action/setLevel',[\Tchoblond59\LaraLight\Controllers\LaraLightController::class, 'setLevel']);
        Route::post('/LaraLight/config/create',[\Tchoblond59\LaraLight\Controllers\LaraLightController::class, 'createConfig']);
        Route::post('/LaraLight/config/createPeriod',[\Tchoblond59\LaraLight\Controllers\LaraLightController::class, 'createPeriod']);
        Route::get('/LaraLight/widget/{id}',[\Tchoblond59\LaraLight\Controllers\LaraLightController::class, 'config']);
        Route::get('/LaraLight/widget/period/{id}',[\Tchoblond59\LaraLight\Controllers\LaraLightController::class, 'period']);
        Route::get('/LaraLight/widget/periodConfig/{id}',[\Tchoblond59\LaraLight\Controllers\LaraLightController::class, 'periodConfig']);
        Route::post('/LaraLight/config/assignPeriod',[\Tchoblond59\LaraLight\Controllers\LaraLightController::class, 'assignPeriod']);
        Route::post('/LaraLight/config/createCommand',[\Tchoblond59\LaraLight\Controllers\LaraLightController::class, 'createCommand']);
        Route::post('/LaraLight/widget/configuration/{id}',[\Tchoblond59\LaraLight\Controllers\LaraLightController::class, 'postConfiguration']);
        Route::post('/LaraLight/mode/update',[\Tchoblond59\LaraLight\Controllers\LaraLightController::class, 'updateMode']);
});
