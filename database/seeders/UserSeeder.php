<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Jane UserAdmin',
            'email' => 'jane@example.com',
            'password' => '$2a$10$SgfxJYuOU2kkoSuJZNl63e/Rpzlz5xz.vrreD7Y.ZO7KKnp77KOPC',
            'created_at' => Carbon::now(),
        ]);
        DB::table('users')->insert([
            'name' => 'Bob Moderator',
            'email' => 'bob@example.com',
            'password' => '$2a$10$SgfxJYuOU2kkoSuJZNl63e/Rpzlz5xz.vrreD7Y.ZO7KKnp77KOPC',
            'created_at' => Carbon::now(),
        ]);
        DB::table('users')->insert([
            'name' => 'Susan ThemeAdmin',
            'email' => 'susan@example.com',
            'password' => '$2a$10$SgfxJYuOU2kkoSuJZNl63e/Rpzlz5xz.vrreD7Y.ZO7KKnp77KOPC',
            'created_at' => Carbon::now(),
        ]);
    }
}
