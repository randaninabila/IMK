<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Notifikasi.php
class Notifikasi extends Model
{
    protected $table      = 'notifikasi';
    protected $primaryKey = 'notifikasi_id';

    public $timestamps = false;
    
    protected $fillable = [
        'user_id', 'pesan', 'tipe', 'status_baca',
    ];

    protected $casts = [
        'created_at'  => 'datetime',
    ];

    // Relasi ke user (opsional)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper: ikon & warna per tipe
    public function getIconConfig(): array
    {
        return match($this->tipe) {
            'booking' => [
                'bg'    => 'bg-[#FFF1F3]',
                'color' => 'text-[#EB2D55]',
                'path'  => 'M8.25 6.75V4.5m7.5 2.25V4.5m-9 6h10.5m-13.5 9h16.5A2.25 2.25 0 0021 17.25V6.75A2.25 2.25 0 0018.75 4.5H5.25A2.25 2.25 0 003 6.75v10.5A2.25 2.25 0 005.25 19.5z',
            ],
            'jadwal' => [
                'bg'    => 'bg-[#FFF4A9]',
                'color' => 'text-[#FF8A00]',
                'path'  => 'M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0018 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 00-2.312 6.022c1.733.64 3.56 1.08 5.454 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0',
            ],
            'sistem' => [
                'bg'    => 'bg-[#EFE3FF]',
                'color' => 'text-[#8D46FF]',
                'path'  => 'M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z',
            ],
            default => [
                'bg'    => 'bg-[#E8F0FF]',
                'color' => 'text-[#3B82F6]',
                'path'  => 'M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z',
            ],
        };
    }

    // Helper: label judul per tipe
    public function getTitleLabel(): string
    {
        return match($this->tipe) {
            'booking' => 'Booking Baru',
            'jadwal'  => 'Pengingat Jadwal',
            'sistem'  => 'Informasi Sistem',
            default   => 'Notifikasi',
        };
    }

    // Helper: waktu relatif
    public function getWaktuLabel(): string
    {
        return $this->created_at->diffForHumans(null, false, true, 1);
    }
}
