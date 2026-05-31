<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PaketCabang extends Model
{
    protected $table = 'paket_cabang';
    protected $primaryKey = 'paket_id';
    public $timestamps = false;

    public function paketLayanan()
    {
        return $this->belongsTo(PaketLayanan::class, 'paket_id');
    }

    public function details()
    {
        return $this->hasMany(PaketDetail::class, 'paket_id');
    }
}