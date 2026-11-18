<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Reaction\CommentReactionController;
use App\Http\Controllers\Api\V1\Reaction\PostReactionController;

use Illuminate\Support\Facades\Route;

Route::get('/posts/{post}/reactions', [PostReactionController::class, 'index']);
Route::put('/posts/{post}/reactions', [PostReactionController::class, 'store']);
Route::post('/posts/{post}/reactions', [PostReactionController::class, 'store']);
Route::delete('/posts/{post}/reactions', [PostReactionController::class, 'destroy']);

Route::get('/comments/{comment}/reactions', [CommentReactionController::class, 'index']);
Route::put('/comments/{comment}/reactions', [CommentReactionController::class, 'store']);
Route::post('/comments/{comment}/reactions', [CommentReactionController::class, 'store']);
Route::delete('/comments/{comment}/reactions', [CommentReactionController::class, 'destroy']);
