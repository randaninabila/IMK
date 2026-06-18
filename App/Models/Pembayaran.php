<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'pembayaran_id';

    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'metode_pembayaran',
        'jumlah',
        'status',
        'tanggal_bayar'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}