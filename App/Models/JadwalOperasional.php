<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalOperasional extends Model
{
    protected $table      = 'jadwal_operasional';
    protected $primaryKey = 'jadwal_id';
    public $timestamps    = false;

    protected $fillable = [
        'cabang_id',
        'hari',
        'jam_buka',
        'jam_tutup',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'cabang_id');
    }
}