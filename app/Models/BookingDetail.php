<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    protected $table = 'booking_detail';
    protected $primaryKey = 'booking_detail_id';

    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'layanan_cabang_id',
        'pegawai_id'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function layananCabang()
    {
        return $this->belongsTo(LayananCabang::class, 'layanan_cabang_id');
    }
}