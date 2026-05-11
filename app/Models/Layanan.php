<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';

    protected $primaryKey = 'layanan_id';

    public function album()
    {
        return $this->hasMany(Album::class, 'layanan_id', 'layanan_id');
    }
}
