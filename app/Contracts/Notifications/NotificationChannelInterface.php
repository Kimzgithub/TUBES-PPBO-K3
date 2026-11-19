<?php

namespace App\Contracts\Notifications;

use App\Models\User;
use App\Domain\Notifications\NotificationMessage;

interface NotificationChannelInterface
{
    public function supports(User $recipient): bool;

    public function send(
        User $recipient,
        NotificationMessage $message
    ): void;
}