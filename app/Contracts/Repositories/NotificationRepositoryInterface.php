<?php

namespace App\Repositories;

use App\Contracts\Repositories\NotificationRepositoryInterface;
use App\Domain\Notifications\NotificationMessage;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function create(User $recipient, NotificationMessage $message): Notification
    {
        $subject = $message->subject();

        return Notification::create([
            'recipient_id' => $recipient->id,
            'actor_id' => $message->actor()->id,
            'type' => $message->type()->value,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'message' => $message->body(),
        ]);
    }

    public function forRecipient(
        User $recipient,
        bool $unreadOnly = false,
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = Notification::where('recipient_id', $recipient->id)
            ->latest();

        if ($unreadOnly) {
            $query->whereNull('read_at');
        }

        return $query->paginate($perPage);
    }

    public function unreadCount(User $recipient): int
    {
        return Notification::where('recipient_id', $recipient->id)
            ->whereNull('read_at')
            ->count();
    }

    public function markAsRead(Notification $notification): Notification
    {
        $notification->update([
            'read_at' => now(),
        ]);

        return $notification->refresh();
    }

    public function markAllAsRead(User $recipient): int
    {
        return Notification::where('recipient_id', $recipient->id)
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);
    }

    public function delete(Notification $notification): void
    {
        $notification->delete();
    }
}