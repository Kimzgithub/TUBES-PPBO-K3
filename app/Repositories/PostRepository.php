<?php

namespace App\Repositories;

use App\Contracts\Repositories\PostRepositoryInterface;
use App\Models\PollOption;
use App\Models\PollVote;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostRepository implements PostRepositoryInterface
{
    public function createPost(User $author, array $attr): Post
    {
        return $author->posts()->create($attr);
    }

    public function addMedia(Post $post, array $items): void
    {
        foreach ($items as $item) {
            $post->media()->create($item);
        }
    }

    public function addPollOptions(Post $post, array $opts): void
    {
        foreach ($opts as $text) {
            $post->pollOptions()->create([
                'option_text' => $text,
                'votes_count' => 0,
            ]);
        }
    }

    public function findPollOption(Post $post, int $optionId): ?PollOption
    {
        return $post->pollOptions()->where('id', $optionId)->first();
    }

    public function hasVoted(Post $post, User $voter): bool
    {
        return PollVote::where('post_id', $post->id)
            ->where('user_id', $voter->id)
            ->exists();
    }

    public function createPollVote(PollOption $opt, User $voter): PollVote
    {
        $vote = PollVote::create([
            'post_id' => $opt->post_id,
            'poll_option_id' => $opt->id,
            'user_id' => $voter->id,
        ]);

        $opt->increment('votes_count');

        return $vote;
    }

    public function findById(int $id): ?Post
    {
        return Post::with(['author', 'media', 'pollOptions'])
            ->withCount(['comments', 'reactions'])
            ->find($id);
    }

    public function feedFor(User $user, int $perPage = 15): LengthAwarePaginator
    {
        $followingIds = $user->following()->pluck('users.id')->push($user->id);

        return Post::with(['author', 'media', 'pollOptions'])
            ->withCount(['comments', 'reactions'])
            ->whereIn('user_id', $followingIds)
            ->latest()
            ->paginate($perPage);
    }

    public function update(Post $post, array $data): Post
    {
        $post->update(['caption' => $data['caption'] ?? $post->caption]);

        return $this->findById($post->id);
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }
}
