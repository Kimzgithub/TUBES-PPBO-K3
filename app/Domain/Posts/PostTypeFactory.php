<?php

namespace App\Domain\Posts;

use App\Enums\PostType;
use App\Models\User;

class PostTypeFactory
{
    public static function make(User $author, array $payload): AbstractPost
    {
        $type = $payload['type'] instanceof PostType
            ? $payload['type']
            : PostType::from((string) $payload['type']);

        return match ($type) {
            PostType::Text => new TextPost($author, $payload),
            PostType::Media => new MediaPost($author, $payload),
            PostType::Poll => new PollPost($author, $payload),
        };
    }
}
