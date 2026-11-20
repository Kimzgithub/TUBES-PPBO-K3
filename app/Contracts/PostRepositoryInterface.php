<?php

namespace App\Contracts\Repositories;

use App\Models\PollOption;
use App\Models\PollVote;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface {
    public function createPost(User $author, array $attr): Post;

    public function addMedia(Post $post, array $items): void;

    public function addPollOptions(Post $post, array $opts): void;

    public function findPollOption(Post $post, int $optionId): ?PollOption;

    public function hasVoted(Post $post, User $voter): bool;

    public function createPollVote(PollOption $opt, User $voter): PollVote;

    public function findById(int $id): ?Post;

    public function feedFor(User $user, int $perPage = 15): LengthAwarePaginator;

    public function update(Post $post, array $data): Post;

    public function delete(Post $post): void;
}
