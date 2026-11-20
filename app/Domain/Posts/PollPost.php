<?php

namespace App\Domain\Posts;

use App\Contracts\Repositories\PostRepositoryInterface;
use App\Enums\PostType;
use App\Exceptions\BusinessRuleException;
use App\Models\PollVote;
use App\Models\Post;
use App\Models\User;
use DateTimeInterface;

class PollPost extends AbstractPost
{
    private array $options = [];

    private string|DateTimeInterface|null $pollEndsAt = null;

    public function type(): PostType
    {
        return PostType::Poll;
    }

    public function validatePayload(): void
    {
        $options = $this->payload['options'] ?? [];
        if (! is_array($options)) {
            throw new BusinessRuleException('Format pilihan jawaban polling harus berupa array.');
        }

        $this->options = array_values($options);
        $this->pollEndsAt = $this->payload['poll_ends_at'] ?? null;

        if (empty(trim((string) ($this->payload['caption'] ?? '')))) {
            throw new BusinessRuleException('Polling wajib memiliki pertanyaan pada caption.');
        }

        if (count($this->options) < 2 || count($this->options) > 4) {
            throw new BusinessRuleException('Polling wajib memiliki 2 hingga 4 pilihan jawaban.');
        }
    }

    protected function afterPersist(Post $post, PostRepositoryInterface $repo): void
    {
        $repo->addPollOptions($post, $this->options);
    }

    public function recordVote(Post $post, User $voter, int $optionId, PostRepositoryInterface $repo): PollVote
    {
        if ($post->type !== PostType::Poll) {
            throw new BusinessRuleException('Postingan ini bukan polling.');
        }

        if ($post->poll_ends_at && $post->poll_ends_at->isPast()) {
            throw new BusinessRuleException('Waktu voting untuk polling ini sudah berakhir.');
        }

        $option = $repo->findPollOption($post, $optionId);
        if (! $option) {
            throw new BusinessRuleException('Pilihan opsi tidak ditemukan pada polling ini.');
        }

        if ($repo->hasVoted($post, $voter)) {
            throw new BusinessRuleException('Kamu sudah memberikan suara pada polling ini.');
        }

        return $repo->createPollVote($option, $voter);
    }
}
