<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        UserRole::factory()->create([
            'name' => 'Admin',
        ]);

        User::create([
            'firstname' => 'Paulino',
            'lastname' => 'Admin',
            'email' => 'paulinopjc@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('26676712'),
            'remember_token' => Str::random(10),
            'role_id' => 1
        ]);

        UserRole::factory(10)->create();
        User::factory(30)->create();

    }
}
