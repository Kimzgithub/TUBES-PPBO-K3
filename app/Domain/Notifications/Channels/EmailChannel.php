<?php

namespace App\Domain\Notifications\Channels;

use App\Contracts\Notifications\NotificationChannelInterface;
use App\Domain\Notifications\NotificationMessage;
use App\Mail\NotificationMail;
use App\Models\User;
use Illuminate\Contracts\Mail\Mailer;

final class EmailChannel implements NotificationChannelInterface
{
    public function __construct(
        private readonly Mailer $mailer,
    ) {}

    public function supports(User $recipient): bool
    {
        return (bool) $recipient->notify_via_email;
    }

    public function send(User $recipient, NotificationMessage $message): void
    {
        $this->mailer->to($recipient->email)->send(new NotificationMail($recipient, $message));
    }
}