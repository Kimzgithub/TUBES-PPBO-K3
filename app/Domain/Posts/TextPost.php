<?php

namespace App\Domain\Posts;

use App\Contracts\Repositories\PostRepositoryInterface;
use App\Enums\PostType;
use App\Exceptions\BusinessRuleException;
use App\Models\Post;

class TextPost extends AbstractPost
{
    public function type(): PostType
    {
        return PostType::Text;
    }

    public function validatePayload(): void
    {
        if (empty(trim((string) ($this->payload['caption'] ?? '')))) {
            throw new BusinessRuleException('Postingan teks wajib memiliki caption.');
        }

        if (! empty($this->payload['media']) || ! empty($this->payload['options'])) {
            throw new BusinessRuleException('Postingan teks tidak boleh menyertakan file media atau opsi polling.');
        }
    }

    protected function afterPersist(Post $post, PostRepositoryInterface $repo): void
    {
        // TextPost hanya menyimpan data ke tabel utama posts
    }
}
