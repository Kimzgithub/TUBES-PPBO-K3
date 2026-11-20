<?php

namespace App\Http\Resources\Api\V1\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'recipient_id' => $this->recipient_id,
            'actor_id' => $this->actor_id,
            'type' => $this->type,
            'message' => $this->message,
            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
        ];
    }
}