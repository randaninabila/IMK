<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlbumFoto extends Model
{
    protected $table = 'album_foto';

    protected $primaryKey = 'foto_id';

    public $timestamps = false;

    protected $fillable = [
        'album_id',
        'url_foto',
        'tipe'
    ];

    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id', 'album_id');
    }
}