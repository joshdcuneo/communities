<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Josh Cuneo',
            'email' => 'josh@critical.codes',
        ]);

        Community::factory()->forOwner($user)->hasUsers(10)->create();
        Community::factory()->hasAttached($user)->create();
    }
}
