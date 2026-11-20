<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
    ) {}

    public function search(Request $request): JsonResponse
    {
        $keyword = (string) $request->query('q', '');
        $result = $this->users->search($keyword);

        return $this->success(UserResource::collection($result), 'Hasil pencarian user.');
    }

    public function show(User $user): JsonResponse
    {
        $user->loadCount(['posts', 'followers', 'following']);

        return $this->success(new UserResource($user), 'Profil user berhasil diambil.');
    }
}