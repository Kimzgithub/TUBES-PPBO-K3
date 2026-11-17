<?php

namespace App\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
   
    public function register(): void
    {
        $map = [
            'App\Contracts\Repositories\UserRepositoryInterface' => 'App\Repositories\UserRepository',
            'App\Contracts\Repositories\PostRepositoryInterface' => 'App\Repositories\PostRepository',
            'App\Contracts\Repositories\CommentRepositoryInterface' => 'App\Repositories\CommentRepository',
            'App\Contracts\Repositories\ReactionRepositoryInterface' => 'App\Repositories\ReactionRepository',
            'App\Contracts\Repositories\NotificationRepositoryInterface' => 'App\Repositories\NotificationRepository',
        ];

        foreach ($map as $interface => $implementation) {
            if (interface_exists($interface) && class_exists($implementation)) {
                $this->app->bind($interface, $implementation);
            }
        }

        $inApp = 'App\Domain\Notifications\Channels\InAppChannel';
        $email = 'App\Domain\Notifications\Channels\EmailChannel';
        $service = 'App\Services\Notification\NotificationService';
        $notifRepo = 'App\Contracts\Repositories\NotificationRepositoryInterface';

        $channels = array_filter([$inApp, $email], fn (string $cls) => class_exists($cls));
        if (! empty($channels)) {
            $this->app->tag($channels, 'notification.channels');
        }

        if (class_exists($service) && interface_exists($notifRepo)) {
            $this->app->bind($service, function (Application $app) use ($service, $notifRepo) {
                return new $service(
                    $app->tagged('notification.channels'),
                    $app->make($notifRepo)
                );
            });
        }
    }
}
