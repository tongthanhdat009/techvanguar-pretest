<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin accounts
        $admins = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@techvanguard.com',
                'password' => 'password',
                'bio' => 'System administrator with full access to all features.',
                'role' => User::ROLE_ADMIN,
                'status' => User::STATUS_ACTIVE,
                'experience_points' => 5000,
                'daily_streak' => 30,
                'last_studied_at' => now()->subHours(2),
            ],
            [
                'name' => 'Moderator Admin',
                'email' => 'moderator@techvanguard.com',
                'password' => 'password',
                'bio' => 'Content moderator and community manager.',
                'role' => User::ROLE_ADMIN,
                'status' => User::STATUS_ACTIVE,
                'experience_points' => 2500,
                'daily_streak' => 15,
                'last_studied_at' => now()->subDay(),
            ],
        ];

        foreach ($admins as $admin) {
            User::firstOrCreate(
                ['email' => $admin['email']],
                [...$admin, 'password' => bcrypt($admin['password'])]
            );
        }

        // Client accounts - Test users
        $clients = [
            [
                'name' => 'John Learner',
                'email' => 'john@example.com',
                'password' => 'password',
                'bio' => 'Passionate about learning new languages and programming.',
                'role' => User::ROLE_CLIENT,
                'status' => User::STATUS_ACTIVE,
                'experience_points' => 1250,
                'daily_streak' => 7,
                'last_studied_at' => now()->subHours(4),
            ],
            [
                'name' => 'Sarah Student',
                'email' => 'sarah@example.com',
                'password' => 'password',
                'bio' => 'Medical student using flashcards for exam prep.',
                'role' => User::ROLE_CLIENT,
                'status' => User::STATUS_ACTIVE,
                'experience_points' => 850,
                'daily_streak' => 5,
                'last_studied_at' => now()->subHours(1),
            ],
            [
                'name' => 'Mike Developer',
                'email' => 'mike@example.com',
                'password' => 'password',
                'bio' => 'Full-stack developer learning new technologies.',
                'role' => User::ROLE_CLIENT,
                'status' => User::STATUS_ACTIVE,
                'experience_points' => 2100,
                'daily_streak' => 12,
                'last_studied_at' => now()->subMinutes(30),
            ],
            [
                'name' => 'Emily Teacher',
                'email' => 'emily@example.com',
                'password' => 'password',
                'bio' => 'Language teacher creating decks for students.',
                'role' => User::ROLE_CLIENT,
                'status' => User::STATUS_ACTIVE,
                'experience_points' => 3200,
                'daily_streak' => 20,
                'last_studied_at' => now()->subHours(6),
            ],
            [
                'name' => 'Inactive User',
                'email' => 'inactive@example.com',
                'password' => 'password',
                'bio' => 'User account currently inactive.',
                'role' => User::ROLE_CLIENT,
                'status' => User::STATUS_INACTIVE,
                'experience_points' => 150,
                'daily_streak' => 0,
                'last_studied_at' => null,
            ],
        ];

        foreach ($clients as $client) {
            User::firstOrCreate(
                ['email' => $client['email']],
                [...$client, 'password' => bcrypt($client['password'])]
            );
        }

        // Additional random users
        User::factory(10)->create(['role' => User::ROLE_CLIENT, 'status' => User::STATUS_ACTIVE]);
    }
}
