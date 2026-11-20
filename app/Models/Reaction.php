<?php

namespace App\Models;

use App\Enums\ReactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reaction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'reactable_type',
        'reactable_id',
        'type',
        'create_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => ReactionType::class,
        ];
    }

    public function reactable(): MorphTo
    {
        return $this->morpTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
