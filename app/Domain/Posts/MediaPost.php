<?php

namespace App\Domain\Posts;

use App\Contracts\Repositories\PostRepositoryInterface;
use App\Enums\MediaType;
use App\Enums\PostType;
use App\Exceptions\BusinessRuleException;
use App\Models\Post;
use App\Traits\HandlesFileUpload;
use Illuminate\Http\UploadedFile;

class MediaPost extends AbstractPost
{
    use HandlesFileUpload;

    /** @var array<int, UploadedFile> */
    private array $uploadedFiles = [];

    public function type(): PostType
    {
        return PostType::Media;
    }

    public function validatePayload(): void
    {
        $media = $this->payload['media'] ?? [];
        if (! is_array($media)) {
            throw new BusinessRuleException('Format daftar file media harus berupa array.');
        }

        $this->uploadedFiles = array_values($media);

        if (count($this->uploadedFiles) === 0) {
            throw new BusinessRuleException('Postingan media wajib menyertakan minimal 1 file gambar atau video.');
        }

        $this->validateMediaMimeAndSize();
    }

    private function validateMediaMimeAndSize(): void
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'video/mp4', 'video/quicktime'];
        $maxBytes = 50 * 1024 * 1024; // 50 MB

        foreach ($this->uploadedFiles as $file) {
            if (! $file instanceof UploadedFile) {
                throw new BusinessRuleException('File media tidak valid.');
            }

            if (! in_array($file->getMimeType(), $allowedMimes, true)) {
                throw new BusinessRuleException('Format file harus berupa JPG, PNG, WEBP, MP4, atau MOV.');
            }

            if ((int) $file->getSize() > $maxBytes) {
                throw new BusinessRuleException('Ukuran tiap file media maksimal 50 MB.');
            }
        }
    }

    protected function afterPersist(Post $post, PostRepositoryInterface $repo): void
    {
        $mediaItems = [];

        foreach ($this->uploadedFiles as $index => $file) {
            $mime = (string) $file->getMimeType();
            $mediaItems[] = [
                'media_type' => str_starts_with($mime, 'video/') ? MediaType::Video : MediaType::Image,
                'file_path' => $this->storeUploadedFile($file, 'posts'),
                'mime_type' => $mime,
                'file_size' => (int) $file->getSize(),
                'sort_order' => $index,
            ];
        }

        $repo->addMedia($post, $mediaItems);
    }
}
