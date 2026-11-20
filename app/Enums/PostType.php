<?php

namespace App\Enums;

enum PostType: string
{
    case Text = 'text';
    case Media = 'media';
    case Poll = 'poll';
}
