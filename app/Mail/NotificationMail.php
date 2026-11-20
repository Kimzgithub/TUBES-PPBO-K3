<?php

namespace App\Mail;

use App\Domain\Notifications\NotificationMessage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $recipient,
        public NotificationMessage $notificationMessage,
    ) {}

    public function build(): static
    {
        return $this->subject($this->notificationMessage->title())
            ->view('emails.notification');
    }
}
