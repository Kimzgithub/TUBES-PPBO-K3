<?php

namespace App\Providers;

use App\Contracts\Repositories\NotificationRepositoryInterface;
use App\Domain\Notifications\Channels\EmailChannel;
use App\Domain\Notifications\Channels\InAppChannel;
use App\Repositories\NotificationRepository;
use App\Services\Notification\NotificationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            NotificationRepositoryInterface::class,
            NotificationRepository::class
        );

        $this->app->tag([
            InAppChannel::class,
            EmailChannel::class,
        ], 'notification.channels');

        $this->app->when(NotificationService::class)
            ->needs('$channels')
            ->giveTagged('notification.channels');
    }

    public function boot(): void
    {
        //
    }
}