<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jane = DB::table('users')->where('name', 'Jane UserAdmin')->first();
        $bob = DB::table('users')->where('name', 'Bob Moderator')->first();
        $susan = DB::table('users')->where('name', 'Susan ThemeAdmin')->first();

        $user_admin = DB::table('roles')->where('name', 'User Administrator')->first();
        $moderator = DB::table('roles')->where('name', 'Moderator')->first();
        $theme_manager = DB::table('roles')->where('name', 'Theme Manager')->first();

        DB::table('role_user')->insert([
            'user_id' => $jane->id,
            'role_id' => $user_admin->id
        ]);
        DB::table('role_user')->insert([
            'user_id' => $bob->id,
            'role_id' => $moderator->id
        ]);
        DB::table('role_user')->insert([
            'user_id' => $susan->id,
            'role_id' => $theme_manager->id
        ]);
    }
}
