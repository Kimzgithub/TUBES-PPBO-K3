<?php

namespace App\Enums;

enum NotificationType: string
{
    case Follow = 'follow';
    case Comment = 'comment';
    case Reaction = 'reaction';
    case PollVote = 'poll_vote';
}
