<?php

namespace App\Http\Requests\Reaction;

use App\Enums\ReactionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReactionRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'type'=> ['required', Rule::enum(ReactionType::class)],
        ];
    }
}
