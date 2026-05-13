<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class FooterComposer
{
    public function compose(View $view): void
    {
        $cabangList = DB::table('cabang as c')
            ->leftJoin('jadwal_operasional as jo', function ($join) {
                $join->on('jo.cabang_id', '=', 'c.cabang_id')
                     ->where('jo.hari', '=', 'senin');
            })
            ->where('c.status', 'BUKA')
            ->select(
                'c.cabang_id',
                'c.nama_cabang',
                'c.alamat',
                'c.status',
                'jo.jam_buka',
                'jo.jam_tutup'
            )
            ->get();

        $salon = DB::table('salon')->first();

        $view->with([
            'cabangList' => $cabangList,
            'salon' => $salon,
        ]);
    }
}