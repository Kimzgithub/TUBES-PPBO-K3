<?php

namespace App\Contracts\Repositories;

use App\Enums\ReactionType;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface ReactionRepositoryInterface
{
    public function listFor(Model $target, int $perPage = 15): LengthAwarePaginator;

    public function summaryFor(Model $target): array;

    public function upsert(Model $target, User $user, ReactionType $type): Reaction;

    public function remove (Model $target, User $user): bool;
}
