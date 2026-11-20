<?php

namespace App\Contracts\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function create(array $data): User;

    public function findByEmail(string $email): ?User;

    public function findByUsername(string $username): ?User;

    public function search(string $keyword, int $perPage = 15): LengthAwarePaginator;

    public function update(User $user, array $data): User;

    public function delete(User $user): void;

    public function isFollowing(User $follower, User $target): bool;

    public function follow(User $follower, User $target): void;

    public function unfollow(User $follower, User $target): void;

    public function followers(User $user, int $perPage = 15): LengthAwarePaginator;

    public function following(User $user, int $perPage = 15): LengthAwarePaginator;
}
