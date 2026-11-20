<?php

namespace App\Http\Controllers\Api\V1\Comment;

use App\Contracts\Repositories\CommentRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Services\Comment\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function __construct(
        private readonly CommentService $commentService,
        private readonly CommentRepositoryInterface $comments,
    ) {}

    public function index(Post $post): JsonResponse
    {
        $list = $this->comments->forPost($post);

        return $this->success(CommentResource::collection($list), 'Daftar komentar berhasil diambil.');
    }

    public function store(StoreCommentRequest $request, Post $post): JsonResponse
    {
        $comment = $this->commentService->store($post, $request->user(), $request->validated());

        return $this->created(new CommentResource($comment), 'Komentar berhasil ditambahkan.');
    }

    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        $updated = $this->commentService->update($comment, $request->validated());

        return $this->success(new CommentResource($updated), 'Komentar berhasil diperbarui.');
    }

    public function destroy(Comment $comment): JsonResponse
    {
        Gate::authorize('delete', $comment);
        $this->commentService->delete($comment);

        return $this->success(null, 'Komentar berhasil dihapus.');
    }
}