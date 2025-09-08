<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users mẫu
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'phone' => '0123456789',
            'gender' => 'male',
            'role' => 'quanly',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Truong Ca',
            'email' => 'truongca@example.com',
            'phone' => '0987654321',
            'gender' => 'female',
            'role' => 'truongca',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Ky Thuat',
            'email' => 'kythuat@example.com',
            'phone' => '0901234567',
            'gender' => 'male',
            'role' => 'ky_thuat',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Ban Hang',
            'email' => 'banhang@example.com',
            'phone' => '0912345678',
            'gender' => 'female',
            'role' => 'ban_hang',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Nha Lien Hoan',
            'email' => 'nhalienhoan@example.com',
            'phone' => '0934567890',
            'gender' => 'male',
            'role' => 'nha_lien_hoan',
            'password' => Hash::make('password'),
        ]);
    }
}
