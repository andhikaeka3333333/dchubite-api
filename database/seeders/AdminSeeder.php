<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'dchubite@gmail.com'],
            [
                'name' => 'Admin Dchubite',
                'password' => Hash::make('dchubite10'),
            ]
        );
    }
}
