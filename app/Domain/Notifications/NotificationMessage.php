<?php

namespace App\Domain\Notifications;

use App\Enums\NotificationType;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

final class NotificationMessage
{
    public function __construct(
        private readonly NotificationType $type,
        private readonly User $actor,
        private readonly string $body,
        private readonly ?Model $subject = null,
    ) {}

    public function type(): NotificationType
    {
        return $this->type;
    }

    public function actor(): User
    {
        return $this->actor;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function subject(): ?Model
    {
        return $this->subject;
    }
}