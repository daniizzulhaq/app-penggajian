<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Departement extends Model
{
    protected $fillable = ['nama_departement', 'kode_departement'];
 
    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }
}