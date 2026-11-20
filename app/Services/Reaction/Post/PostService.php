<?php

namespace App\Services\Post;

use App\Contracts\Repositories\PostRepositoryInterface;
use App\Domain\Notifications\NotificationMessage;
use App\Domain\Posts\PollPost;
use App\Domain\Posts\PostTypeFactory;
use App\Enums\NotificationType;
use App\Models\Comment;
use App\Models\PollVote;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\HandlesFileUpload;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PostService
{
    use HandlesFileUpload;

    public function __construct(
        private readonly PostRepositoryInterface $repo,
        private readonly ?NotificationService $notifier = null,
    ) {}

    public function feed(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repo->feedFor($user, $perPage);
    }

    public function create(User $author, array $payload): Post
    {
        $domainPost = PostTypeFactory::make($author, $payload);

        return $domainPost->createUsing($this->repo);
    }

    public function update(Post $post, array $data): Post
    {
        return $this->repo->update($post, $data);
    }

    public function delete(Post $post): void
    {
        DB::transaction(function () use ($post): void {
            // 1. Hapus semua file media fisik dari storage publik
            foreach ($post->media as $media) {
                $this->deleteStoredFile($media->file_path);
            }

            // 2. Hapus semua reaction polimorfik pada komentar & post ini di MySQL
            if (class_exists(Reaction::class)) {
                if (class_exists(Comment::class)) {
                    $commentIds = $post->comments()->pluck('id');
                    if ($commentIds->isNotEmpty()) {
                        Reaction::where('reactable_type', Comment::class)
                            ->whereIn('reactable_id', $commentIds)
                            ->delete();
                    }
                }

                Reaction::where('reactable_type', Post::class)
                    ->where('reactable_id', $post->id)
                    ->delete();
            }

            // 3. Hapus postingan dari database
            $this->repo->delete($post);
        });
    }

    public function vote(Post $post, User $voter, int $optionId): PollVote
    {
        $pollDomain = new PollPost($post->author, []);
        $vote = $pollDomain->recordVote($post, $voter, $optionId, $this->repo);

        if ($this->notifier && class_exists(NotificationMessage::class)) {
            $this->notifier->notify($post->author, new NotificationMessage(
                actor: $voter,
                type: NotificationType::PollVote,
                title: 'Suara Baru pada Polling',
                body: "{$voter->username} memberikan suara pada polling kamu.",
                data: ['post_id' => $post->id, 'poll_option_id' => $optionId],
            ));
        }

        return $vote;
    }
}
