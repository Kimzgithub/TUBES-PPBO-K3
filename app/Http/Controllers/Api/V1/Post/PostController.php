<?php

namespace App\Http\Controllers\Api\V1\Post;

use App\Contracts\Repositories\PostRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Requests\Post\VotePollRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use App\Services\Post\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller {
    public function __construct(
        private readonly PostService $postService,
        private readonly PostRepositoryInterface $posts,
    ) {}

    public function feed(Request $request): JsonResponse
    {
        $feed = $this->postService->feed($request->user());

        return $this->success(PostResource::collection($feed), 'Feed berhasil diambil.');
    }public function __construct(
        private readonly PostService $postService,
        private readonly PostRepositoryInterface $posts,
    ) {}

    public function feed(Request $request): JsonResponse
    {
        $feed = $this->postService->feed($request->user());

        return $this->success(PostResource::collection($feed), 'Feed berhasil diambil.');
    }
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = $this->postService->create($request->user(), $request->validated());

        return $this->created(new PostResource($post), 'Postingan berhasil dibuat.');
    }

    public function show(Post $post): JsonResponse
    {
        $detail = $this->posts->findById($post->id);

        return $this->success(new PostResource($detail), 'Detail postingan berhasil diambil.');
    }
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $updated = $this->postService->update($post, $request->validated());

        return $this->success(new PostResource($updated), 'Postingan berhasil diperbarui.');
    }

    public function destroy(Post $post): JsonResponse
    {
        Gate::authorize('delete', $post);
        $this->postService->delete($post);

        return $this->success(null, 'Postingan berhasil dihapus.');
    }
    public function vote(VotePollRequest $request, Post $post): JsonResponse
    {
        $this->postService->vote($post, $request->user(), (int) $request->validated('poll_option_id'));
        $updated = $this->posts->findById($post->id);

        return $this->success(new PostResource($updated), 'Suara berhasil tercatat.');
    }
}
