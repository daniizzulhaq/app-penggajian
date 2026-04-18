<?php
// ================================================================
// FILE: database/seeders/DatabaseSeeder.php  (REPLACE)
// ================================================================
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
 
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            JabatanSeeder::class,
            DepartementSeeder::class,
        ]);
    }
}
 