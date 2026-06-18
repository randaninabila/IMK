<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PaketCabang extends Model
{
    protected $table = 'paket_cabang';
    protected $primaryKey = 'paket_cabang_id';
    public $timestamps = false;

    protected $fillable = [
        'paket_id', 'cabang_id', 'harga_normal', 'harga_promo', 'status'
    ];

    public function paketLayanan()
    {
        return $this->belongsTo(PaketLayanan::class, 'paket_id');
    }

    public function details()
    {
        return $this->hasMany(PaketDetail::class, 'paket_id');
    }
}