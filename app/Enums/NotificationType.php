<?php

namespace App\Enums;

enum NotificationType: string
{
    case FOLLOW = 'follow';
    case COMMENT = 'comment';
    case REACTION = 'reaction';
    case POLL_VOTE = 'poll_vote';
}