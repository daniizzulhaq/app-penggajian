<?php

namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\Jabatan;
 
class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $jabatans = [
            ['nama_jabatan' => 'Direktur',            'gaji_pokok' => 15000000],
            ['nama_jabatan' => 'Manager',              'gaji_pokok' => 10000000],
            ['nama_jabatan' => 'Supervisor',           'gaji_pokok' => 7000000],
            ['nama_jabatan' => 'Staff Senior',         'gaji_pokok' => 5000000],
            ['nama_jabatan' => 'Staff Junior',         'gaji_pokok' => 3500000],
            ['nama_jabatan' => 'Admin',                'gaji_pokok' => 3000000],
            ['nama_jabatan' => 'Operator',             'gaji_pokok' => 2800000],
            ['nama_jabatan' => 'Security',             'gaji_pokok' => 2500000],
        ];
 
        foreach ($jabatans as $j) {
            Jabatan::create($j);
        }
    }
}