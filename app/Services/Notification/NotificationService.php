<?php

namespace App\Services\Notification;

use App\Contracts\Notifications\NotificationChannelInterface;
use App\Domain\Notifications\NotificationMessage;
use App\Models\User;

final class NotificationService
{
    /**
     * @param iterable<NotificationChannelInterface> $channels
     */
    public function __construct(
        private readonly iterable $channels,
    ) {}

    public function notify(User $recipient, NotificationMessage $message): void
    {
        if ($recipient->is($message->actor())) {
            return;
        }

        foreach ($this->channels as $channel) {
            if ($channel->supports($recipient)) {
                $channel->send($recipient, $message);
            }
        }
    }
}