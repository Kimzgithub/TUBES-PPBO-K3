<?php

namespace App\Services\Comment;

use App\Contracts\Repositories\CommentRepositoryInterface;
use App\Domain\Notifications\NotificationMessage;
use App\Enums\NotificationType;
use App\Exceptions\BusinessRuleException;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\DB;

class CommentService
{
    public function __construct(
        private readonly CommentRepositoryInterface $repo,
        private readonly ?NotificationService $notifier = null,
    ) {}

    public function store(Post $post, User $author, array $data): Comment
    {
        if (!empty($data['parent_id'])) {
            $parent = $this->repo->findById((int) $data['parent_id']);

            if (!$parent || $parent->post_id !== $post->id) {
                throw new BusinessRuleException('Komentar induk tidak berasal dari postingan yang sama.');
            }

            // Batasi nested reply maksimal 1 level
            $data['parent_id'] = $parent->parent_id ?? $parent->id;
        }

        $comment = $this->repo->create($post, $author, $data);

        if ($this->notifier && class_exists(NotificationMessage::class)) {
            $this->notifier->notify($post->author, new NotificationMessage(
                actor: $author,
                type: NotificationType::Comment,
                title: 'Komentar Baru',
                body: "{$author->username} mengomentari postingan kamu.",
                data: ['post_id' => $post->id, 'comment_id' => $comment->id],
            ));
        }

        return $comment;
    }

    public function update(Comment $comment, array $data): Comment
    {
        return $this->repo->update($comment, $data);
    }

    public function delete(Comment $comment): void
    {
        DB::transaction(function () use ($comment): void {
            if (class_exists(Reaction::class)) {
                $replyIds = $comment->replies()->pluck('id')->push($comment->id);
                Reaction::where('reactable_type', Comment::class)
                    ->whereIn('reactable_id', $replyIds)
                    ->delete();
            }

            $this->repo->delete($comment);
        });
    }
}