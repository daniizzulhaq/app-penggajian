<?php

// ================================================================
// FILE: app/Models/Payroll.php
// ================================================================
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Payroll extends Model
{
    protected $fillable = [
        'karyawan_id', 'bulan', 'tahun', 'hari_kerja', 'hari_hadir',
        'hari_izin', 'hari_sakit', 'hari_alpha', 'total_jam_lembur',
        'gaji_pokok', 'total_tunjangan', 'uang_lembur', 'total_potongan',
        'potongan_alpha', 'gaji_bersih', 'status', 'dibuat_oleh',
        'diapprove_oleh', 'approved_at', 'paid_at'
    ];
 
    protected $casts = [
        'approved_at' => 'datetime',
        'paid_at'     => 'datetime',
        'gaji_bersih' => 'decimal:2',
    ];
 
    public function karyawan()       { return $this->belongsTo(Karyawan::class); }
    public function details()        { return $this->hasMany(PayrollDetail::class); }
    public function dibuatOleh()     { return $this->belongsTo(User::class, 'dibuat_oleh'); }
    public function diapproveOleh()  { return $this->belongsTo(User::class, 'diapprove_oleh'); }
 
    public function getNamaBulanAttribute(): string
    {
        $bulan = ['', 'Januari','Februari','Maret','April','Mei','Juni',
                  'Juli','Agustus','September','Oktober','November','Desember'];
        return $bulan[$this->bulan] ?? '';
    }
}
