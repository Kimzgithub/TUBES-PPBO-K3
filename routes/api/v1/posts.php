<?php

use App\Http\Controllers\Api\V1\Post\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/feed', [PostController::class, 'feed']);
Route::post('/posts', [PostController::class, 'store']);
Route::get('/posts/{post}', [PostController::class, 'show']);
Route::put('/posts/{post}', [PostController::class, 'update']);
Route::patch('/posts/{post}', [PostController::class, 'update']);
Route::delete('/posts/{post}', [PostController::class, 'destroy']);
Route::post('/posts/{post}/vote', [PostController::class, 'vote']);
