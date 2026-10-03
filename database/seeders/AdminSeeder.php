<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@movie.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]);

        User::Create(
            ['name' =>'raja',
            'email' => 'raja@gmail.com',
            'password' =>Hash::make('raja123'),
            'role' => 'customer',
            ]);
    }
}