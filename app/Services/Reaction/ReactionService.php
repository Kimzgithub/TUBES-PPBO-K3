<?php
namespace App\Services\Reaction;

use App\Contracts\Repositories\ReactionRepositoryInterface;
use App\Domain\Notifications\NotificationMessage;
use App\Enums\NotificationType;
use App\Enums\ReactionType;
use App\Models\Reaction;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Database\Eloquent\Model;

class ReactionService {
    public function __construct(
        private readonly ReactionRepositoryInterface $repo,
        private readonly ?NotificationService $notifier = null,
    ){}

    public function react(Model $target, User $user, ReactionType $type): Reaction {
        $reaction = $this->repo->upsert($target, $user, $type);
        if ($reaction->wasRecentlyCreated || $reaction->wasChanged('type')) { $owner = $target->author ?? null;
        if ($owner instanceof User && $this->notifier && class_exists(NotificationMessage::class)) {
            $this->notifier->notify($owner, new NotificationMessage(
                actor: $user,
                type: NotificationType::Reaction,
                title: 'Reaksi Baru',
                body: "{$user->username} memberikan reaksi {$type->value}.",
                data: [
                    'reactable_type' => class_basename($target),
                    'reactable_id' => $target->getKey(),
                    'reaction' => $type->value,
                ],
            ));
        }
    }

    return $reaction->load('user');
    }

    public function unreact(Model $target, User $user): bool {
        return $this->repo->remove($target, $user);
    }
}
