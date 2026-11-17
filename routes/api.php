<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    require __DIR__.'/api/v1/auth.php';

    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function (): void {
        require __DIR__.'/api/v1/users.php';
        require __DIR__.'/api/v1/posts.php';
        require __DIR__.'/api/v1/comments.php';
        require __DIR__.'/api/v1/reactions.php';
        require __DIR__.'/api/v1/notifications.php';
    });
});
