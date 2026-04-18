<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Potongan extends Model
{
    protected $fillable = ['karyawan_id', 'nama_potongan', 'nominal', 'jenis'];
    protected $casts    = ['nominal' => 'decimal:2'];
 
    public function karyawan() { return $this->belongsTo(Karyawan::class); }
}