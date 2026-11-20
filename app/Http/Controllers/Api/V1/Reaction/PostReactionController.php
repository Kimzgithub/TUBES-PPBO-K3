<?php

namespace App\Http\Controllers\Api\V1\Reaction;

use App\Contracts\Repositories\ReactionRepositoryInterface;
use App\Enums\ReactionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reaction\StoreReactionRequest;
use App\Http\Resources\Reaction\ReactionResource;
use App\Models\Post;
use App\Services\Reaction\ReactionService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostReactionController extends Controller {
    public function __construct(
        private readonly ReactionService $reactionService,
        private readonly ReactionRepositoryInterface $reactions,

    ){}

    public function index(Post $post): JsonResponse {
        return $this->success([
            'summary' => $this->reactions->summaryFor($post),
            'items' => ReactionResource::collection($this->reactions->listFor($post)),
        ], 'Daftar reaksi postingan berhasil diambil');
    }

    public function store(StoreReactionRequest $request, Post $post): JsonResponse {
        $type = ReactionType::from($request->validated('type'));
        $reaction = $this->reactionService->react($post, $request->user(), $type);

        return $this->success(new ReactionResource($reaction), 'Reaksi pada postingan berhasil disimpan.');
    }

    public function destroy(Request $request, Post $post): JsonResponse {
        $this->reactionService->unreact($post, $request->user());

        
        return $this->success(null, 'Reaksi pada postingan berhasil dihapus.');
    }
}
