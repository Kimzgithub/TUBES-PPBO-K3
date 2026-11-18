<?php

namespace App\Repositories;

use App\Contracts\Repositories\ReactionRepositoryInterface;
use App\Enums\ReactionType;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Override;

class ReactionRepository implements ReactionRepositoryInterface {
    public function listFor(Model $target, int $perPage = 15):
    LengthAwarePaginator {
        return $target->reactions()->with('user')->latest('id')->paginate($perPage);
    }

    public function summaryFor(Model $target): array
    {
        return $target->reactionSummary();
    }

    public function upsert(Model  $target, User $user, ReactionType $type): Reaction {
        return Reaction::updateOrCreate([
            'user_id' => $user->id,
            'reactable_type' => $target->getMorphClass(),
            'reactable_id' => $target->getKey(),
        ],[
            'type'=> $type,
        ]);
    }

    public function remove(Model $target, User $user): bool {
        return (bool) $target->reactions() ->where ('user_id', $user->id)->delete();
    }
}
