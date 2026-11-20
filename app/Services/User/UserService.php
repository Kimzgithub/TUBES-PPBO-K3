<?php

namespace App\Services\User;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Domain\Notifications\NotificationMessage;
use App\Enums\NotificationType;
use App\Exceptions\BusinessRuleException;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\HandlesFileUpload;
use Illuminate\Http\UploadedFile;

class UserService
{
    use HandlesFileUpload;

    public function __construct(
        private readonly UserRepositoryInterface $repo,
        private readonly ?NotificationService $notifier = null,
    ) {}

    public function updateProfile(User $user, array $data): User
    {
        if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
            $this->deleteStoredFile($user->avatar_path);
            $data['avatar_path'] = $this->storeUploadedFile($data['avatar'], 'avatars');
            unset($data['avatar']);
        }

        return $this->repo->update($user, $data);
    }

    public function deleteAccount(User $user): void
    {
        $this->deleteStoredFile($user->avatar_path);
        $user->tokens()->delete();
        $this->repo->delete($user);
    }

    public function follow(User $follower, User $target): void
    {
        if ($follower->is($target)) {
            throw new BusinessRuleException('Kamu tidak dapat mengikuti akun sendiri.');
        }

        if (! $this->repo->isFollowing($follower, $target)) {
            $this->repo->follow($follower, $target);

            if ($this->notifier && class_exists(NotificationMessage::class)) {
                $this->notifier->notify($target, new NotificationMessage(
                    actor: $follower,
                    type: NotificationType::Follow,
                    title: 'Pengikut Baru',
                    body: "{$follower->username} mulai mengikuti kamu.",
                    data: ['follower_username' => $follower->username],
                ));
            }
        }
    }

    public function unfollow(User $follower, User $target): void
    {
        $this->repo->unfollow($follower, $target);
    }
}
