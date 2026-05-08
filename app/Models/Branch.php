<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'address'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function specialists()
    {
        return $this->hasMany(User::class)->where('role', 'specialist');
    }

    public function admins()
    {
        return $this->hasMany(User::class)->where('role', 'admin');
    }
}