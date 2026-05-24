<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); // ambil data user yang login
        $pegawai = $user->pegawai; // relasi ke tabel pegawai

        return view('pegawai.profile.prof1', compact('user', 'pegawai'));
    }

    public function edit(string $id)
{
    $user = Auth::user();
    $pegawai = $user->pegawai;

    return view('pegawai.profile.edit', compact('user', 'pegawai'));
}

public function update(Request $request, string $id)
{
    $user = Auth::user();

    $request->validate([
        'nama'  => 'required|string|max:255',
        'no_hp' => 'nullable|string|max:20',
        'foto_profile' => 'nullable|image|max:2048',
    ]);

    $data = [
        'nama'  => $request->nama,
        'no_hp' => $request->no_hp,
    ];

    if ($request->hasFile('foto_profile')) {
        $path = $request->file('foto_profile')->store('foto_profile', 'public');
        $data['foto_profile'] = $path;
    }

    $user->update($data);

    return redirect()->route('pegawai.profile.index')->with('success', 'Profil berhasil diperbarui.');
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

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(string $id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
