<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'pegawai_id';
    public $timestamps = false;
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'cabang_id',
        'status_kerja'
    ];

    // =====================
    // RELATIONS
    // =====================
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'cabang_id');
    }
}