<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'booking_id';

    public $timestamps = false;

    protected $fillable = [
        'pelanggan_id',
        'tanggal_booking',
        'jam_booking',
        'status',
        'tipe_booking',
        'created_by'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'booking_id');
    }

    public function details()
    {
        return $this->hasMany(BookingDetail::class, 'booking_id');
    }
}