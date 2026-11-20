<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findByUsername(string $username): ?User
    {
        return User::withCount(['posts', 'followers', 'following'])
            ->where('username', $username)
            ->first();
    }

    public function search(string $keyword, int $perPage = 15): LengthAwarePaginator
    {
        return User::withCount(['followers', 'following'])
            ->where('username', 'like', "%{$keyword}%")
            ->orWhere('name', 'like', "%{$keyword}%")
            ->paginate($perPage);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user->refresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function isFollowing(User $follower, User $target): bool
    {
        return Follow::where('follower_id', $follower->id)
            ->where('following_id', $target->id)
            ->exists();
    }

    public function follow(User $follower, User $target): void
    {
        Follow::firstOrCreate([
            'follower_id' => $follower->id,
            'following_id' => $target->id,
        ]);
    }

    public function unfollow(User $follower, User $target): void
    {
        Follow::where('follower_id', $follower->id)
            ->where('following_id', $target->id)
            ->delete();
    }

    public function followers(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $user->followers()->paginate($perPage);
    }

    public function following(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $user->following()->paginate($perPage);
    }
}
