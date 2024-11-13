<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DataSourceController;

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/connect', [DataSourceController::class, 'connect']);
    Route::post('/datasource/{type}/register', [DataSourceController::class, 'register']);
});