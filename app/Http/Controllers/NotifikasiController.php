<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'semua');
        $userId = auth()->id();

        $query = Notifikasi::where('user_id', $userId)
            ->orderBy('created_at', 'desc');

        // Filter
        if ($filter === 'belum-dibaca') {
            $query->where('status_baca', 'belum');
        } elseif (in_array($filter, ['booking', 'jadwal', 'sistem'])) {
            $query->where('tipe', $filter);
        }

        $notifikasi = $query->get();

        // Pisah notifikasi
        $belumDibaca = $notifikasi->where('status_baca', 'belum');

        $sebelumnya = $notifikasi->where('status_baca', 'dibaca');

        return view('pegawai.notifikasi.not1', compact(
            'belumDibaca',
            'sebelumnya',
            'filter'
        ));
    }

    public function markAsRead($id)
    {
        $notif = Notifikasi::where('notifikasi_id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notif->status_baca = 'dibaca';
        $notif->saveQuietly();

        return back();
    }

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
