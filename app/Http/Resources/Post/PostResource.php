<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $request->user();

        return [
            'id' => $this->id,
            'type' => $this->type?->value ?? $this->type,
            'caption' => $this->caption,
            'poll_ends_at' => $this->poll_ends_at?->toIso8601String(),
            'author' => class_exists(UserResource::class)
                ? new UserResource($this->whenLoaded('author'))
                : $this->whenLoaded('author'),
            'media' => PostMediaResource::collection($this->whenLoaded('media')),
            'poll_options' => PollOptionResource::collection($this->whenLoaded('pollOptions')),
            'comments_count' => $this->whenCounted('comments'),
            'reactions_count' => $this->whenCounted('reactions'),
            'is_reacted_by_me' => ($user && method_exists($this->resource, 'isReactedBy'))
                ? $this->resource->isReactedBy($user)
                : false,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
