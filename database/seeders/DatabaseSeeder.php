<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Jay Bryan',
            'username' => 'jay',
            'email' => 'jay@laravel.com',
            'password' => 'jay',
        ]);

        $this->call(RoomSeeder::class);
        $this->call(ServiceSeeder::class);
    }
}
