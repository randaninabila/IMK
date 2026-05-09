<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayananCabang extends Model
{
    protected $table = 'layanan_cabang';
    protected $primaryKey = 'layanan_cabang_id';

    public $timestamps = false;

    protected $fillable = [
        'layanan_id',
        'cabang_id',
        'harga',
        'harga_promo',
        'status'
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
}