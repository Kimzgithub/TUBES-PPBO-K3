<?php

namespace App\Domain\Notifications;

use App\Enums\NotificationType;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

final class NotificationMessage
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        private readonly NotificationType $type,
        private readonly User $actor,
        private readonly string $body,
        private readonly string $title = 'Notifikasi Baru',
        private readonly array $data = [],
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

    public function title(): string
    {
        return $this->title;
    }

    public function body(): string
    {
        return $this->body;
    }

    /**
     * @return array<string, mixed>
     */
    public function data(): array
    {
        return $this->data;
    }

    public function subject(): ?Model
    {
        return $this->subject;
    }
}
