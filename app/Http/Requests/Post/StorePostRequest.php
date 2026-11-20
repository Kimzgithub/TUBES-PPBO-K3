<?php

namespace App\Http\Requests\Post;

use App\Enums\PostType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(PostType::class)],
            'caption' => ['nullable', 'string', 'max:2200'],
            'media' => ['nullable', 'array', 'max:10'],
            'media.*' => ['file', 'mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/quicktime', 'max:51200'],
            'options' => ['nullable', 'array', 'min:2', 'max:4'],
            'options.*' => ['string', 'distinct', 'max:100'],
            'poll_ends_at' => ['nullable', 'date', 'after:now'],
        ];
    }
}
