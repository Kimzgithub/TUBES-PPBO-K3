<?php

namespace App\Repositories;

use App\Contracts\Repositories\CommentRepositoryInterface;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CommentRepository implements CommentRepositoryInterface
{
    public function forPost(Post $post, int $perPage = 15): LengthAwarePaginator
    {
        return $post->comments()
            ->whereNull('parent_id')
            ->with(['author', 'replies.author'])
            ->withCount('reactions')
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): ?Comment
    {
        return Comment::with(['author', 'replies.author'])->find($id);
    }

    public function create(Post $post, User $author, array $data): Comment
    {
        $comment = $post->comments()->create([
            'user_id' => $author->id,
            'parent_id' => $data['parent_id'] ?? null,
            'body' => $data['body'],
        ]);

        return $comment->load(['author', 'replies.author']);
    }

    public function update(Comment $comment, array $data): Comment
    {
        $comment->update(['body' => $data['body']]);

        return $comment->refresh()->load(['author', 'replies.author']);
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }
}