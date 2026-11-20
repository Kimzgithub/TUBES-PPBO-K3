<?php

namespace App\Http\Controllers\Api\V1\Reaction;

use App\Contracts\Repositories\ReactionRepositoryInterface;
use App\Enums\ReactionType;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reaction\StoreReactionRequest;
use App\Http\Resources\Reaction\ReactionResource;
use App\Models\Comment;
use App\Services\Reaction\ReactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentReactionController extends Controller {
    public function __construct(
        private readonly ReactionService $reactionService,
        private readonly ReactionRepositoryInterface $reactions,

    ){}

    public function index(Comment $comment): JsonResponse {
        return $this->success([
            'summary' => $this->reactions->summaryFor($comment),
            'items' => ReactionResource::collection($this->reactions->listFor($comment)),
        ],'Daftar reaksi komentar berhasil diambil.');
    }

    public function store(StoreReactionRequest $request, Comment $comment): JsonResponse {
        $type = ReactionType::from($request->validated('type'));
        $reaction = $this->reactionService->react($comment, $request->user(), $type);

        return $this->success(new ReactionResource($reaction), 'Reaksi pada komentar berhasil disimpan.');
    }

    public function destroy(Request $request, Comment $comment): JsonResponse {
        $this->reactionService->unreact($comment, $request->user());
        
        return $this->success(null, 'Reaksi pada komentar berhasil dihapus.');
    }
}
