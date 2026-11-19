<?php

namespace App\Contracts\Repositories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CommentRepositoryInterface
{
    public function forPost(Post $post, int $perPage = 15): LengthAwarePaginator;
    public function findById(int $id): ?Comment;
    public function create(Post $post, User $author, array $data): Comment;
    public function update(Comment $comment, array $data): Comment;
    public function delete(Comment $comment): void;
}