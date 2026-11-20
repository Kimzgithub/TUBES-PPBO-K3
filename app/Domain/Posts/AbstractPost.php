<?php

namespace App\Domain\Posts;

use App\Contracts\Repositories\PostRepositoryInterface;
use App\Enums\PostType;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;

abstract class AbstractPost
{
    public function __construct(
        protected User $author,
        protected array $payload,
    ) {}

    abstract public function type(): PostType;

    abstract public function validatePayload(): void;

    abstract protected function afterPersist(Post $post, PostRepositoryInterface $repo): void;

    protected function baseAttributes(): array
    {
        return [
            'type' => $this->type(),
            'caption' => $this->payload['caption'] ?? null,
            'poll_ends_at' => $this->payload['poll_ends_at'] ?? null,
        ];
    }

    public function createUsing(PostRepositoryInterface $repo): Post
    {
        $this->validatePayload();

        return DB::transaction(function () use ($repo): Post {
            $post = $repo->createPost($this->author, $this->baseAttributes());
            $this->afterPersist($post, $repo);

            return $repo->findById($post->id);
        });
    }
}
