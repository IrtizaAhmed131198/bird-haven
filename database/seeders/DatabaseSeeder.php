<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@aviaryarchive.com'],
            [
                'name'     => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        $this->call([
            CategorySeeder::class,
            BirdSeeder::class,
            PolicyPagesSeeder::class,
            AboutPageSeeder::class,
            AccessorySeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
