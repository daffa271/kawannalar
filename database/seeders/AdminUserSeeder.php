<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Fadhil Muhammad Daffa (Admin 1)',
                'email' => 'daffa@kawannalar.id',
                'password' => Hash::make('Fadhil2701'),
                'role' => 'admin',
                'status' => 'active',
            ],
            [
                'name' => ' Edelweis Vitto Brata Irawan (Admin 2)',
                'email' => 'edelweis@kawannalar.id',
                'password' => Hash::make('VittoPunk1756'),
                'role' => 'admin',
                'status' => 'active',
            ],
            [
                'name' => 'Anisa Ayuk Lestari (Admin 3)',
                'email' => 'anisa@kawannalar.id',
                'password' => Hash::make('AyukPlaosan2208'),
                'role' => 'admin',
                'status' => 'active',
            ],
            [
                'name' => 'Lailatul Musarofah',
                'email' => 'lailatul@kawannalar.id',
                'password' => Hash::make('Itul2026'),
                'role' => 'admin',
                'status' => 'active',
            ],
        ];

        foreach ($admins as $adminData) {
            User::updateOrCreate(
                ['email' => $adminData['email']],
                $adminData
            );
        }
    }
}
