<?php

use App\Http\Controllers\Api\V1\User\FollowController;
use App\Http\Controllers\Api\V1\User\ProfileController;
use App\Http\Controllers\Api\V1\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/profile', [ProfileController::class, 'show']);
Route::put('/profile', [ProfileController::class, 'update']);
Route::post('/profile', [ProfileController::class, 'update']);
Route::delete('/profile', [ProfileController::class, 'destroy']);

Route::get('/users/search', [UserController::class, 'search']);
Route::get('/users/{user:username}', [UserController::class, 'show']);

Route::post('/users/{user:username}/follow', [FollowController::class, 'store']);
Route::delete('/users/{user:username}/follow', [FollowController::class, 'destroy']);
Route::get('/users/{user:username}/followers', [FollowController::class, 'followers']);
Route::get('/users/{user:username}/following', [FollowController::class, 'following']);