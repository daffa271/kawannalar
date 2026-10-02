<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Password awal admin tidak boleh ditulis di source code — isi di .env lokal.
        $password = env('ADMIN_SEED_PASSWORD');

        if (blank($password)) {
            $this->command?->warn('ADMIN_SEED_PASSWORD belum diisi di .env — AdminUserSeeder dilewati.');

            return;
        }

        $admins = [
            ['name' => 'Fadhil Muhammad Daffa (Admin 1)', 'email' => 'daffa@kawannalar.id'],
            ['name' => ' Edelweis Vitto Brata Irawan (Admin 2)', 'email' => 'edelweis@kawannalar.id'],
            ['name' => 'Anisa Ayuk Lestari (Admin 3)', 'email' => 'anisa@kawannalar.id'],
            ['name' => 'Lailatul Musarofah', 'email' => 'lailatul@kawannalar.id'],
        ];

        foreach ($admins as $admin) {
            // firstOrCreate: akun admin yang sudah ada tidak diubah, password tidak di-reset.
            User::firstOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => Hash::make($password),
                    'role' => 'admin',
                    'status' => 'active',
                ]
            );
        }
    }
}
