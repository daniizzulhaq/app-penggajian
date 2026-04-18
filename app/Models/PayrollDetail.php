<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class PayrollDetail extends Model
{
    protected $fillable = ['payroll_id', 'tipe', 'keterangan', 'nominal'];
    protected $casts    = ['nominal' => 'decimal:2'];
 
    public function payroll() { return $this->belongsTo(Payroll::class); }
}