<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisLayanan extends Model
{
    use HasFactory;

    protected $table = 'jenis_layanan';

    protected $primaryKey = 'jenis_layanan_id';

    public $timestamps = false; // kalau tabel kamu tidak punya created_at & updated_at

    protected $fillable = [
        'nama_jenis',
        'deskripsi',
    ];
}