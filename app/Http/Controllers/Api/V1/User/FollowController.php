<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly UserRepositoryInterface $users,
    ) {}

    public function store(Request $request, User $user): JsonResponse
    {
        $this->userService->follow($request->user(), $user);

        return $this->success(null, "Berhasil mengikuti {$user->username}.");
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        $this->userService->unfollow($request->user(), $user);

        return $this->success(null, "Berhenti mengikuti {$user->username}.");
    }

    public function followers(User $user): JsonResponse
    {
        $list = $this->users->followers($user);

        return $this->success(UserResource::collection($list), 'Daftar followers berhasil diambil.');
    }

    public function following(User $user): JsonResponse
    {
        $list = $this->users->following($user);

        return $this->success(UserResource::collection($list), 'Daftar following berhasil diambil.');
    }
}