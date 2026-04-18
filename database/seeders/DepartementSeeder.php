<?php

// ================================================================
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\Departement;
 
class DepartementSeeder extends Seeder
{
    public function run(): void
    {
        $departements = [
            ['nama_departement' => 'Human Resource',       'kode_departement' => 'HRD'],
            ['nama_departement' => 'Finance & Accounting', 'kode_departement' => 'FIN'],
            ['nama_departement' => 'Information Technology','kode_departement' => 'IT'],
            ['nama_departement' => 'Marketing',            'kode_departement' => 'MKT'],
            ['nama_departement' => 'Operasional',          'kode_departement' => 'OPS'],
            ['nama_departement' => 'Produksi',             'kode_departement' => 'PRD'],
            ['nama_departement' => 'Logistik',             'kode_departement' => 'LOG'],
        ];
 
        foreach ($departements as $d) {
            Departement::create($d);
        }
    }
}
 