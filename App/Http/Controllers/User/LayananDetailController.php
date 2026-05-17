<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LayananDetailController extends Controller
{
    public function show($layanan_id)
    {
        $layanan = DB::table('layanan')
            ->where('layanan_id', $layanan_id)
            ->first();

        return view('user.service.detail', compact('layanan'));
    }
}