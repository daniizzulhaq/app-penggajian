<?php

// ================================================================
// FILE: app/Models/Absensi.php
// ================================================================
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Absensi extends Model
{
    protected $fillable = ['karyawan_id', 'tanggal', 'status', 'jam_lembur', 'keterangan'];
    protected $casts    = ['tanggal' => 'date', 'jam_lembur' => 'decimal:2'];
 
    public function karyawan() { return $this->belongsTo(Karyawan::class); }
}