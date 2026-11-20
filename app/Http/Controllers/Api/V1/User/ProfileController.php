<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\DeleteAccountRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\User\ProfileResource;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->loadCount(['posts', 'followers', 'following']);

        return $this->success(new ProfileResource($user), 'Profil berhasil diambil.');
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->userService->updateProfile($request->user(), $request->validated());

        return $this->success(new ProfileResource($user), 'Profil berhasil diperbarui.');
    }

    public function destroy(DeleteAccountRequest $request): JsonResponse
    {
        $this->userService->deleteAccount($request->user());

        return $this->success(null, 'Akun berhasil dihapus.');
    }
}
