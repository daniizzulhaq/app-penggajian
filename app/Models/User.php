<?php
// ================================================================
// FILE: app/Models/User.php  (REPLACE existing)
// ================================================================
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
 
class User extends Authenticatable
{
    use HasFactory, Notifiable;
 
    protected $fillable = ['name', 'email', 'password', 'role', 'is_active'];
    protected $hidden   = ['password', 'remember_token'];
    protected $casts    = ['email_verified_at' => 'datetime', 'password' => 'hashed'];
 
    public function isAdmin(): bool    { return in_array($this->role, ['admin', 'hrd']); }
    public function isKaryawan(): bool { return $this->role === 'karyawan'; }
    public function karyawan()         { return $this->hasOne(Karyawan::class); }
}