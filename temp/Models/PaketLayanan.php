<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PaketLayanan extends Model
{
    protected $table = 'paket_layanan';
    protected $primaryKey = 'paket_id';

    public $timestamps = false;

    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'kategori_pelanggan'
    ];
}