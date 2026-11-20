<?php

namespace Database\Seeders;

use App\Enums\PostType;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder {
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        Post::create([
            'user_id' => $user->id,
            'type' => PostType::Text,
            'caption' => 'Selamat datang di Instagram Clone Kelompok 3!',
        ]);

        $poll = Post::create([
            'user_id' => $user->id,
            'type' => PostType::Poll,
            'caption' => 'Konsep OOP mana yang paling kamu sukai?',
            'poll_ends_at' => now()->addDays(7),
        ]);

        $poll->pollOptions()->createMany([
            ['option_text' => 'Inheritance', 'votes_count' => 0],
            ['option_text' => 'Polymorphism', 'votes_count' => 0],
            ['option_text' => 'Encapsulation', 'votes_count' => 0],
        ]);
    }
}
