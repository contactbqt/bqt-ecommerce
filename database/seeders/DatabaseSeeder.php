<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Community;
use App\Models\LeadershipWord;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        //Community::factory(10)->create();
        //LeadershipWord::factory(10)->create();

        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
