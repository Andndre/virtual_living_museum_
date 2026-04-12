<?php

use App\Http\Controllers\Api\AnnotationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/museums/{museum}/annotations', [AnnotationController::class, 'index']);
