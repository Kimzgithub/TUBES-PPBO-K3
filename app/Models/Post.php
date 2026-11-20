<?php

namespace App\Models;

use App\Enums\PostType;
use App\Traits\HasReactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory, HasReactions;

    protected $fillable = [
        'user_id',
        'type',
        'caption',
        'poll_ends_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => PostType::class,
            'poll_ends_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(PostMedia::class)->orderBy('sort_order');
    }

    public function pollOptions(): HasMany
    {
        return $this->hasMany(PollOption::class);
    }

    public function pollVotes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
