<?php

namespace App\Enums;

enum ReactionType: string
{
    case Like = 'like';
    case Love = 'love';
    case Laugh = 'laugh';
    case Sad = 'sad';
    case Angry = 'angry';
}
