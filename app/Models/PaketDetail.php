<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PaketDetail extends Model
{
    protected $table = 'paket_detail';
    public $timestamps = false;

    protected $primaryKey = 'paket_detail_id';

    protected $fillable = [
        'paket_id',
        'layanan_id'
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }
}