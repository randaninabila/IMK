<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPegawai extends Model
{
    protected $table = 'jadwal_pegawai';

    protected $primaryKey = 'jadwal_pegawai_id';

    public $timestamps = false;

    protected $fillable = [
        'pegawai_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status_ketersediaan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i:s',
        'jam_selesai' => 'datetime:H:i:s',
    ];
}
