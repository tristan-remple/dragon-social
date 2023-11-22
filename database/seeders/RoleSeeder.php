<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            'name' => 'User Administrator',
            'created_at' => Carbon::now(),
        ]);
        DB::table('roles')->insert([
            'name' => 'Moderator',
            'created_at' => Carbon::now(),
        ]);
        DB::table('roles')->insert([
            'name' => 'Theme Manager',
            'created_at' => Carbon::now(),
        ]);
    }
}
