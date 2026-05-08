<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = true;
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'role',
        'status_akun',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // =====================
    // RELATIONS
    // =====================
    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'user_id', 'user_id');
    }

    public function pelanggan()
    {
        return $this->hasOne(Pelanggan::class, 'user_id', 'user_id');
    }

    // =====================
    // HELPERS
    // =====================
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPegawai(): bool
    {
        return $this->role === 'pegawai';
    }

    public function isPelanggan(): bool
    {
        return $this->role === 'pelanggan';
    }

    // Untuk navbar
    public function getNameAttribute(): string
    {
        return $this->nama ?? '';
    }
}