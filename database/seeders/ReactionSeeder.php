<?php

namespace Database\Seeders;

use App\Enums\ReactionType;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReactionSeeder extends Seeder {
    public function run(): void {
        $post = Post::first();
        $user = User::first();

        if ($post && $user) {
            Reaction::firstOrCreate([
                'user_id' => $user->id,
                'reactable_type' => $post->getMorphClass(),
                'reactable_id' => $post->id,
            ],  [
                'type'=> ReactionType::Like,
            ]);
        }
    }
}
