<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';

    protected $primaryKey = 'layanan_id';

    public $timestamps = false;
    
    protected $fillable = [
        'jenis_layanan_id',
        'nama_layanan',
        'deskripsi',
        'durasi',
        'kategori_pelanggan',
        'cover_foto'
    ];

    public function album()
    {
        return $this->hasMany(Album::class, 'layanan_id', 'layanan_id');
    }

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'jenis_layanan_id');
    }
}
