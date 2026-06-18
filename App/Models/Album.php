<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $table = 'album';

    protected $primaryKey = 'album_id';

    public $timestamps = true;

    protected $fillable = [
        'layanan_id',
        'deskripsi'
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id', 'layanan_id');
    }

    public function fotos()
    {
        return $this->hasMany(AlbumFoto::class, 'album_id', 'album_id');
    }
}