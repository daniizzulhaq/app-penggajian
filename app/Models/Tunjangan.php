<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tunjangan extends Model
{
    protected $fillable = ['karyawan_id', 'nama', 'nominal', 'jenis'];
    protected $casts    = ['nominal' => 'decimal:2'];

    public function karyawan() { return $this->belongsTo(Karyawan::class); }
}