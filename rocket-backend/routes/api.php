<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DocumentController;


Route::get('/documents', [DocumentController::class, 'index']);
Route::post('/documents', [DocumentController::class, 'store']);
Route::post('/documents/{id}/confirm', [DocumentController::class, 'confirm']);



Route::get('/documents/{id}', [DocumentController::class, 'show']);