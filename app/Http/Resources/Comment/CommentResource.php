<?php

namespace App\Http\Resources\Comment;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'post_id' => $this->post_id,
            'parent_id' => $this->parent_id,
            'body' => $this->body,
            'author' => new UserResource($this->whenLoaded('author')),
            'replies' => self::collection($this->whenLoaded('replies')),
            'reactions_count' => $this->whenCounted('reactions'),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}