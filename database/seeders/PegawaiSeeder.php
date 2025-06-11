<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pegawaiUsers = [
            [
                'name' => 'Ahmad Pegawai',
                'email' => 'ahmad@itseeystore.com',
                'password' => Hash::make('password123'),
                'role' => 'pegawai',
            ],
            [
                'name' => 'Siti Pegawai',
                'email' => 'siti@itseeystore.com',
                'password' => Hash::make('password123'),
                'role' => 'pegawai',
            ],
            [
                'name' => 'Budi Pegawai',
                'email' => 'budi@itseeystore.com',
                'password' => Hash::make('password123'),
                'role' => 'pegawai',
            ],
        ];

        foreach ($pegawaiUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        // Also create an admin user if doesn't exist
        User::updateOrCreate(
            ['email' => 'admin@itseeystore.com'],
            [
                'name' => 'Admin Itseey Store',
                'email' => 'admin@itseeystore.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Fix user ID 5 specifically (from error logs)
        User::where('id', 5)->update(['role' => 'admin']);

        // Update ALL existing users without role to have admin role
        User::whereNull('role')->orWhere('role', '')->update(['role' => 'admin']);
    }
}
