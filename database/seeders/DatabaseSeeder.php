<?php

namespace Database\Seeders;

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
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@satas.ma'],
            [
                'name' => 'Admin SATAS',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'phone' => '0600000000',
            ]
        );

        // Client
        User::firstOrCreate(
            ['email' => 'client@test.ma'],
            [
                'name' => 'Client Test',
                'password' => bcrypt('password'),
                'role' => 'client',
                'phone' => '0611111111',
            ]
        );

        $this->call([
            MainSeeder::class,
        ]);
    }
}
