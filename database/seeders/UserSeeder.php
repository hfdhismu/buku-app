<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Cek berdasarkan email
            [
                'name' => 'Administrator',
                'password' => bcrypt('123'),
                'role_id' => 1,
            ]
        );
    }
}
