<?php

namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
 
class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'      => 'Administrator',
            'email'     => 'admin@penggajian.com',
            'password'  => Hash::make('password123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);
 
        // HRD
        User::create([
            'name'      => 'HRD Manager',
            'email'     => 'hrd@penggajian.com',
            'password'  => Hash::make('password123'),
            'role'      => 'hrd',
            'is_active' => true,
        ]);
 
        // Contoh Karyawan
        User::create([
            'name'      => 'Budi Santoso',
            'email'     => 'budi@penggajian.com',
            'password'  => Hash::make('password123'),
            'role'      => 'karyawan',
            'is_active' => true,
        ]);
    }
}