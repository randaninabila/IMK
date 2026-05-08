<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialistProfile extends Model
{
    protected $fillable = ['user_id', 'specialty', 'photo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}