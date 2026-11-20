<?php

namespace App\Traits;

use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasReactions {
    protected static function bootHasReaction(): void {
        static::deleting(function ($model): void {
            $model->reactions()->delete();
        });
    }

    public function reactions(): MorphMany {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function reactionSummary(): MorphMany {
        return $this->reactions()
        ->selectRaw('type, count(*) as total')
        ->groupBy('type')
        ->pluck('total','type')
        ->toArray();
    }

    public function isReactedBy(User $user): bool {
        return $this->reactions()
        ->where('user_id', $user->id)
        ->exists();
    }
}
