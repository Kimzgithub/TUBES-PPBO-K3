<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\User\ProfileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthTokenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'token_type' => 'Bearer',
            'access_token' => $this->resource['token'],
            'user' => new ProfileResource($this->resource['user']),
        ];
    }
}