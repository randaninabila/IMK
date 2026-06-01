<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UlasanAdminController extends Controller
{
    private function getBranches()
    {
        $branches = DB::table('cabang')
            ->select(
                'cabang_id',
                DB::raw('MIN(nama_cabang) as nama_cabang'),
                DB::raw('MIN(alamat) as alamat'),
                DB::raw('MIN(status) as status')
            )
            ->whereIn('cabang_id', [1, 2])
            ->groupBy('cabang_id')
            ->orderBy('cabang_id', 'asc')
            ->get()
            ->map(function ($branch) {
                $namaCabang = strtolower($branch->nama_cabang ?? '');

                $branch->label = ((int) $branch->cabang_id === 2 || str_contains($namaCabang, 'percut'))
                    ? 'Cabang Percut'
                    : 'Cabang Tembung';

                return $branch;
            });

        if ($branches->isEmpty()) {
            $branches = collect([
                (object) [
                    'cabang_id' => 1,
                    'nama_cabang' => 'Salon Muslimah Dina - Tembung',
                    'alamat' => null,
                    'status' => 'BUKA',
                    'label' => 'Cabang Tembung',
                ],
                (object) [
                    'cabang_id' => 2,
                    'nama_cabang' => 'Salon Muslimah Dina - Percut',
                    'alamat' => null,
                    'status' => 'BUKA',
                    'label' => 'Cabang Percut',
                ],
            ]);
        }

        return $branches;
    }

    private function photoStatusSubquery()
    {
        return DB::table('ulasan_foto')
            ->select(
                'ulasan_id',
                DB::raw("MAX(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as has_approved"),
                DB::raw("MAX(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as has_rejected"),
                DB::raw("MAX(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as has_pending"),
                DB::raw('GROUP_CONCAT(url_foto SEPARATOR "|||") as foto_urls')
            )
            ->groupBy('ulasan_id');
    }

    private function statusCase()
    {
        return "
            CASE
                WHEN COALESCE(fs.has_approved, 0) = 1 THEN 'approved'
                WHEN COALESCE(fs.has_rejected, 0) = 1 AND COALESCE(fs.has_pending, 0) = 0 THEN 'rejected'
                ELSE 'pending'
            END
        ";
    }

    private function baseReviewQuery($selectedCabangId = null)
    {
        $photoStatus = $this->photoStatusSubquery();

        $query = DB::table('ulasan as ul')
            ->leftJoin('booking as b', 'b.booking_id', '=', 'ul.booking_id')
            ->leftJoin('pelanggan as pl', 'pl.pelanggan_id', '=', 'ul.pelanggan_id')
            ->leftJoin('users as pelanggan_user', 'pelanggan_user.user_id', '=', 'pl.user_id')
            ->leftJoin('booking_detail as bd', 'bd.booking_id', '=', 'b.booking_id')
            ->leftJoin('layanan_cabang as lc', 'lc.layanan_cabang_id', '=', 'bd.layanan_cabang_id')
            ->leftJoin('layanan as l', 'l.layanan_id', '=', 'lc.layanan_id')
            ->leftJoin('pegawai as pg', 'pg.pegawai_id', '=', 'bd.pegawai_id')
            ->leftJoin('users as pegawai_user', 'pegawai_user.user_id', '=', 'pg.user_id')
            ->leftJoinSub($photoStatus, 'fs', function ($join) {
                $join->on('fs.ulasan_id', '=', 'ul.ulasan_id');
            });

        if ($selectedCabangId) {
            $query->where(function ($branchQuery) use ($selectedCabangId) {
                $branchQuery->where('lc.cabang_id', $selectedCabangId)
                    ->orWhere('pg.cabang_id', $selectedCabangId);
            });
        }

        return $query;
    }

    public function index(Request $request)
    {
        $branches = $this->getBranches();

        $selectedCabangId = $request->query('cabang_id');

        if ($selectedCabangId !== null) {
            $selectedCabangId = (int) $selectedCabangId;
        }

        if ($selectedCabangId && !$branches->contains('cabang_id', $selectedCabangId)) {
            $selectedCabangId = null;
        }

        $selectedBranch = $selectedCabangId
            ? $branches->firstWhere('cabang_id', $selectedCabangId)
            : null;

        $status = $request->query('status', 'pending');

        if (!in_array($status, ['pending', 'approved', 'rejected', 'all'], true)) {
            $status = 'pending';
        }

        $search = trim((string) $request->query('search', ''));
        $statusCase = $this->statusCase();

        $counts = [
            'pending' => (clone $this->baseReviewQuery($selectedCabangId))
                ->whereRaw("{$statusCase} = ?", ['pending'])
                ->distinct('ul.ulasan_id')
                ->count('ul.ulasan_id'),

            'approved' => (clone $this->baseReviewQuery($selectedCabangId))
                ->whereRaw("{$statusCase} = ?", ['approved'])
                ->distinct('ul.ulasan_id')
                ->count('ul.ulasan_id'),

            'rejected' => (clone $this->baseReviewQuery($selectedCabangId))
                ->whereRaw("{$statusCase} = ?", ['rejected'])
                ->distinct('ul.ulasan_id')
                ->count('ul.ulasan_id'),

            'all' => (clone $this->baseReviewQuery($selectedCabangId))
                ->distinct('ul.ulasan_id')
                ->count('ul.ulasan_id'),
        ];

        $reviewsQuery = $this->baseReviewQuery($selectedCabangId);

        if ($status !== 'all') {
            $reviewsQuery->whereRaw("{$statusCase} = ?", [$status]);
        }

        if ($search !== '') {
            $reviewsQuery->where(function ($query) use ($search) {
                $query->where('pelanggan_user.nama', 'like', '%' . $search . '%')
                    ->orWhere('ul.komentar', 'like', '%' . $search . '%')
                    ->orWhere('l.nama_layanan', 'like', '%' . $search . '%');
            });
        }

        $reviews = $reviewsQuery
            ->select(
                'ul.ulasan_id',
                'ul.booking_id',
                'ul.pelanggan_id',
                'ul.rating',
                'ul.komentar',
                'ul.created_at',
                'ul.updated_at',
                'pelanggan_user.nama as pelanggan_nama',
                'pelanggan_user.email as pelanggan_email',
                'pelanggan_user.no_hp as pelanggan_no_hp',
                DB::raw('GROUP_CONCAT(DISTINCT l.nama_layanan ORDER BY l.nama_layanan SEPARATOR ", ") as layanan_nama'),
                DB::raw('COALESCE(MAX(pegawai_user.nama), "-") as pegawai_nama'),
                DB::raw('COALESCE(MAX(lc.cabang_id), MAX(pg.cabang_id)) as cabang_id'),
                'fs.has_approved',
                'fs.has_rejected',
                'fs.has_pending',
                'fs.foto_urls',
                DB::raw("{$statusCase} as status_moderasi")
            )
            ->groupBy(
                'ul.ulasan_id',
                'ul.booking_id',
                'ul.pelanggan_id',
                'ul.rating',
                'ul.komentar',
                'ul.created_at',
                'ul.updated_at',
                'pelanggan_user.nama',
                'pelanggan_user.email',
                'pelanggan_user.no_hp',
                'fs.has_approved',
                'fs.has_rejected',
                'fs.has_pending',
                'fs.foto_urls'
            )
            ->orderByRaw("FIELD({$statusCase}, 'pending', 'approved', 'rejected')")
            ->orderByDesc('ul.created_at')
            ->paginate(8)
            ->withQueryString();

        return view('admin.ulasan.ulasanadmin', compact(
            'branches',
            'selectedCabangId',
            'selectedBranch',
            'status',
            'search',
            'counts',
            'reviews'
        ));
    }

    public function updateStatus(Request $request, $ulasan_id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review = DB::table('ulasan')
            ->where('ulasan_id', $ulasan_id)
            ->first();

        if (!$review) {
            return back()->with('error', 'Ulasan tidak ditemukan.');
        }

        DB::transaction(function () use ($request, $ulasan_id) {
            $hasPhotoStatusRow = DB::table('ulasan_foto')
                ->where('ulasan_id', $ulasan_id)
                ->exists();

            if ($hasPhotoStatusRow) {
                DB::table('ulasan_foto')
                    ->where('ulasan_id', $ulasan_id)
                    ->update([
                        'status' => $request->status,
                    ]);
            } else {
                $data = [
                    'ulasan_id' => $ulasan_id,
                    'url_foto' => null,
                    'status' => $request->status,
                ];

                if (Schema::hasColumn('ulasan_foto', 'foto_id')) {
                    $data['foto_id'] = (DB::table('ulasan_foto')->max('foto_id') ?? 0) + 1;
                }

                if (Schema::hasColumn('ulasan_foto', 'created_at')) {
                    $data['created_at'] = now();
                }

                DB::table('ulasan_foto')->insert($data);
            }

            if (Schema::hasColumn('ulasan', 'updated_at')) {
                DB::table('ulasan')
                    ->where('ulasan_id', $ulasan_id)
                    ->update([
                        'updated_at' => now(),
                    ]);
            }
        });

        $message = match ($request->status) {
            'approved' => 'Ulasan berhasil diterima dan bisa tampil di halaman testimoni.',
            'rejected' => 'Ulasan berhasil ditolak.',
            default => 'Ulasan dikembalikan ke status menunggu.',
        };

        return back()->with('success', $message);
    }
}