<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $table      = 'cabang';
    protected $primaryKey = 'cabang_id';

    protected $fillable = ['nama_cabang', 'alamat', 'status'];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'cabang_id', 'cabang_id');
    }

    public function admins()
    {
        return $this->hasMany(Pegawai::class, 'cabang_id', 'cabang_id')
            ->whereHas('user', fn($q) => $q->where('role', 'admin'));
    }

    public function layananCabang()
    {
        return $this->hasMany(LayananCabang::class, 'cabang_id');
    }
}