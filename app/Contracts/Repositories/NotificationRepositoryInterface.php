<?php

namespace App\Contracts\Repositories;

use App\Domain\Notifications\NotificationMessage;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface
{
    public function create(User $recipient, NotificationMessage $message): Notification;

    public function forRecipient(
        User $recipient,
        bool $unreadOnly = false,
        int $perPage = 15
    ): LengthAwarePaginator;

    public function unreadCount(User $recipient): int;

    public function markAsRead(Notification $notification): Notification;

    public function markAllAsRead(User $recipient): int;

    public function delete(Notification $notification): void;
}
