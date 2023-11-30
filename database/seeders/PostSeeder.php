<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get the users that were already seeded
        $jane = DB::table('users')->where('name', 'Jane UserAdmin')->first();
        $bob = DB::table('users')->where('name', 'Bob Moderator')->first();
        $susan = DB::table('users')->where('name', 'Susan ThemeAdmin')->first();

        // insert posts
        DB::table('posts')->insert([
            'title' => 'Imagine a Dragon',
            'img_filename' => 'project.png',
            'alt' => 'dark blue lizard-like dragon',
            'content' => 'I think dragons are cool.',
            'created_by' => $bob->id,
            'created_at' => Carbon::now(),
        ]);
        DB::table('posts')->insert([
            'title' => 'Raptor',
            'img_filename' => 'wc-1.png',
            'alt' => 'purple raptor-like dragon',
            'content' => 'Dragons are cool but they\'d be cooler if they were like raptors',
            'created_by' => $jane->id,
            'created_at' => Carbon::now(),
        ]);
        DB::table('posts')->insert([
            'title' => 'Feathered',
            'img_filename' => 'coatl-1.png',
            'content' => 'Actually, what if they had feathers?',
            'alt' => 'light blue feathered dragon',
            'created_by' => $jane->id,
            'created_at' => Carbon::now(),
        ]);
        DB::table('posts')->insert([
            'title' => 'Scuttle',
            'img_filename' => 'cerise-scuttle.png',
            'content' => 'What if a dragon had so many legs?',
            'alt' => 'red dragon with a centipede\'s legs',
            'created_by' => $susan->id,
            'created_at' => Carbon::now(),
        ]);
    }
}
