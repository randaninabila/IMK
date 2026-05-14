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

    protected $primaryKey = 'user_id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'foto_profile',
        'role',
        'status_akun',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // =========================
    // RELATIONS
    // =========================

    public function pegawai()
    {
        return $this->hasOne(
            Pegawai::class,
            'user_id',
            'user_id'
        );
    }

    public function pelanggan()
    {
        return $this->hasOne(
            Pelanggan::class,
            'user_id',
            'user_id'
        );
    }

    // =========================
    // ACCESSORS
    // =========================

    public function getNameAttribute(): string
    {
        return $this->nama ?? '';
    }

    public function getInitialAttribute(): string
    {
        return strtoupper(
            substr($this->nama ?? '?', 0, 1)
        );
    }

    public function getFotoProfileUrlAttribute(): ?string
    {
        return $this->foto_profile
            ? asset('storage/' . $this->foto_profile)
            : null;
    }

    // =========================
    // HELPERS
    // =========================

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

    public function isActive(): bool
    {
        return $this->status_akun === 'aktif';
    }

    // Untuk navbar
    public function getNameAttribute(): string
    {
        return $this->nama ?? '';
    }
}