<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class HrAuthorSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'hr@gmail.com'],
            [
                'name' => 'HR Author',
                'password' => Hash::make('admin123'),
                'type' => 'hr-author',
                'lang' => 'en',
                'is_active' => 1,
                'delete_status' => 1,
                'created_by' => 0,
                'email_verified_at' => now(),
            ]
        );
    }
}


