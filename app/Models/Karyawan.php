<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Karyawan extends Model
{
    protected $fillable = [
        'user_id', 'jabatan_id', 'departement_id', 'nik', 'nama_lengkap',
        'email', 'no_telp', 'alamat', 'jenis_kelamin', 'tanggal_lahir',
        'tanggal_masuk', 'status', 'gaji_pokok'
    ];
 
    protected $casts = [
        'tanggal_lahir'  => 'date',
        'tanggal_masuk'  => 'date',
        'gaji_pokok'     => 'decimal:2',
    ];
 
    public function jabatan()      { return $this->belongsTo(Jabatan::class); }
    public function departement()  { return $this->belongsTo(Departement::class); }
    public function user()         { return $this->belongsTo(User::class); }
    public function tunjangens()   { return $this->hasMany(Tunjangan::class); }
    public function potongans()    { return $this->hasMany(Potongan::class); }
    public function absensis()     { return $this->hasMany(Absensi::class); }
    public function payrolls()     { return $this->hasMany(Payroll::class); }
}