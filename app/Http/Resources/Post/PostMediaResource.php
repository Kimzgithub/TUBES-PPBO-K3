<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostMediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'media_type' => $this->media_type?->value ?? $this->media_type,
            'url' => Storage::disk('public')->url($this->file_path),
            'mime_type' => $this->mime_type,
            'file_size' => $this->file_size,
            'sort_order' => $this->sort_order,
        ];
    }
}
