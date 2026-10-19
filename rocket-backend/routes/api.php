<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DocumentController;

Route::post('/documents', [DocumentController::class, 'store']);



Route::get('/documents/{id}', [DocumentController::class, 'show']);