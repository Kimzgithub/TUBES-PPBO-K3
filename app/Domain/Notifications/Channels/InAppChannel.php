<?php

namespace App\Domain\Notifications\Channels;

use App\Contracts\Notifications\NotificationChannelInterface;
use App\Contracts\Repositories\NotificationRepositoryInterface;
use App\Domain\Notifications\NotificationMessage;
use App\Models\User;

final class InAppChannel implements NotificationChannelInterface
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notifications,
    ) {}

    public function supports(User $recipient): bool
    {
        return true;
    }

    public function send(User $recipient, NotificationMessage $message): void
    {
        $this->notifications->create($recipient, $message);
    }
}