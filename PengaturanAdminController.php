<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class PengaturanAdminController extends Controller
{
    private function getLoggedInAdmin()
    {
        if (!Auth::check()) {
            return null;
        }

        $authUser = Auth::user();
        $userId = $authUser->user_id ?? $authUser->id ?? null;

        if (!$userId) {
            return null;
        }

        return DB::table('users')
            ->where('user_id', $userId)
            ->where('role', 'admin')
            ->first();
    }

    public function index()
    {
        $admin = $this->getLoggedInAdmin();

        if (!$admin) {
            return redirect('/login')
                ->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        return view('admin.pengaturan.pengaturanadmin', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = $this->getLoggedInAdmin();

        if (!$admin) {
            return redirect('/login')
                ->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'no_hp' => 'nullable|string|max:20',
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $emailExists = DB::table('users')
            ->where('email', $request->email)
            ->where('user_id', '!=', $admin->user_id)
            ->exists();

        if ($emailExists) {
            return back()
                ->withInput()
                ->with('error', 'Email sudah digunakan oleh akun lain.');
        }

        $photoPath = $admin->foto_profile;

        if ($request->hasFile('foto_profile')) {
            $folder = public_path('profile');

            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
            }

            $file = $request->file('foto_profile');
            $fileName = 'admin_' . $admin->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();

            $file->move($folder, $fileName);

            $photoPath = 'profile/' . $fileName;
        }

        $updateData = [
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'foto_profile' => $photoPath,
        ];

        if (Schema::hasColumn('users', 'updated_at')) {
            $updateData['updated_at'] = now();
        }

        DB::table('users')
            ->where('user_id', $admin->user_id)
            ->where('role', 'admin')
            ->update($updateData);

        return redirect()
            ->route('admin.pengaturan')
            ->with('success', 'Profil admin berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $admin = $this->getLoggedInAdmin();

        if (!$admin) {
            return redirect('/login')
                ->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $currentPasswordValid = Hash::check($request->current_password, $admin->password)
            || hash('sha256', $request->current_password) === $admin->password;

        if (!$currentPasswordValid) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        $updateData = [
            'password' => Hash::make($request->password),
        ];

        if (Schema::hasColumn('users', 'updated_at')) {
            $updateData['updated_at'] = now();
        }

        DB::table('users')
            ->where('user_id', $admin->user_id)
            ->where('role', 'admin')
            ->update($updateData);

        return redirect()
            ->route('admin.pengaturan')
            ->with('success', 'Password admin berhasil diperbarui.');
    }
}