<?php

// ================================================================
// FILE: app/Models/Jabatan.php
// ================================================================
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Jabatan extends Model
{
    protected $fillable = ['nama_jabatan', 'gaji_pokok'];
 
    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }
}