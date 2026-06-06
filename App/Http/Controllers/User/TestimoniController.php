<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestimoniController extends Controller
{
    public function index(Request $request)
    {
        $selectedJenis = $request->input('jenis'); // null = semua

        $testimonials  = $this->getTestimonials($selectedJenis);
        $jenisLayanan  = $this->getJenisLayanan();
        $faqs          = $this->getFaqs();

        return view('user.testimoni.testimoni', compact('testimonials', 'jenisLayanan', 'selectedJenis', 'faqs'));
    }

    /**
     * Ambil semua ulasan dari database.
     * Filter opsional berdasarkan jenis_layanan_id.
     *
     * Relasi:
     *   ulasan → booking_detail → layanan_cabang → layanan → jenis_layanan
     *   ulasan → pelanggan → users (nama)
     *   ulasan → ulasan_foto (foto approved)
     */
    private function getTestimonials(?string $selectedJenis)
    {
        // Subquery: satu foto approved per ulasan
        $fotoSub = DB::table('ulasan_foto')
            ->select('ulasan_id', DB::raw('MIN(url_foto) as foto'))
            ->where('status', 'approved')
            ->groupBy('ulasan_id');

        $query = DB::table('ulasan as u')
            ->leftJoin('pelanggan as p',      'p.pelanggan_id',      '=', 'u.pelanggan_id')
            ->leftJoin('users as us',          'us.user_id',          '=', 'p.user_id')
            ->leftJoin('booking_detail as bd', 'bd.booking_id',       '=', 'u.booking_id')
            ->leftJoin('layanan_cabang as lc', 'lc.layanan_cabang_id','=', 'bd.layanan_cabang_id')
            ->leftJoin('layanan as l',         'l.layanan_id',        '=', 'lc.layanan_id')
            ->leftJoin('jenis_layanan as jl',  'jl.jenis_layanan_id', '=', 'l.jenis_layanan_id')
            ->leftJoinSub($fotoSub, 'uf', fn($j) => $j->on('uf.ulasan_id', '=', 'u.ulasan_id'))
            ->select([
                'u.ulasan_id',
                DB::raw("CASE WHEN u.nama_samar = 1 THEN 'Pelanggan' ELSE COALESCE(us.nama, 'Pelanggan Dina') END as name"),
                DB::raw("COALESCE(u.komentar, 'Ulasan pelanggan belum tersedia.') as comment"),
                DB::raw("COALESCE(u.rating, 5) as rating"),
                DB::raw("COALESCE(
                    GROUP_CONCAT(DISTINCT l.nama_layanan ORDER BY l.nama_layanan SEPARATOR ', '),
                    'Ulasan pelanggan'
                ) as service"),
                DB::raw("MAX(jl.nama_jenis) as jenis"),
                DB::raw("MAX(uf.foto) as photo"),
                'u.created_at as date',
            ])
            ->groupBy('u.ulasan_id', 'us.nama', 'u.komentar', 'u.nama_samar', 'u.rating', 'u.created_at')
            ->orderByDesc('u.created_at');

        // Filter by jenis layanan (jika dipilih)
        if ($selectedJenis) {
            $query->where('l.jenis_layanan_id', $selectedJenis);
        }

        return $query->get()->map(fn($item) => [
            'name'    => $item->name    ?: 'Pelanggan Dina',
            'comment' => $item->comment ?: 'Ulasan pelanggan belum tersedia.',
            'rating'  => (int) ($item->rating ?: 5),
            'service' => $item->service ?: 'Ulasan pelanggan',
            'jenis'   => $item->jenis   ?: null,
            'photo'   => $this->formatPhotoUrl($item->photo ?? null),
            'date'    => $item->date    ?? null,
        ]);
    }

    /**
     * Ambil semua jenis layanan yang memiliki ulasan (untuk tombol filter).
     */
    private function getJenisLayanan(): \Illuminate\Support\Collection
    {
        return DB::table('jenis_layanan as jl')
            ->join('layanan as l',         'l.jenis_layanan_id',  '=', 'jl.jenis_layanan_id')
            ->join('layanan_cabang as lc', 'lc.layanan_id',       '=', 'l.layanan_id')
            ->join('booking_detail as bd', 'bd.layanan_cabang_id','=', 'lc.layanan_cabang_id')
            ->join('ulasan as u',          'u.booking_id',        '=', 'bd.booking_id')
            ->select('jl.jenis_layanan_id', 'jl.nama_jenis')
            ->distinct()
            ->orderBy('jl.jenis_layanan_id')
            ->get();
    }

    private function formatPhotoUrl(?string $photo): ?string
    {
        if (!$photo) return null;
        if (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://') || str_starts_with($photo, 'data:')) {
            return $photo;
        }
        if (str_starts_with($photo, 'storage/')) {
            return asset($photo);
        }
        if (str_starts_with($photo, 'ulasan/')) {
            return asset($photo);  // file ada di public/ulasan/, bukan storage
        }
        return asset('storage/' . $photo);
    }

    private function getFaqs(): array
    {
        return [
            [
                'question' => 'Apakah perawatan ini aman dan cocok untuk semua jenis kulit?',
                'answer'   => 'Sebagian besar perawatan aman untuk berbagai jenis kulit. Namun, kondisi kulit setiap orang berbeda, sehingga kami tetap menyarankan konsultasi singkat sebelum perawatan dimulai.',
                'open'     => false,
            ],
            [
                'question' => 'Apakah saya perlu konsultasi sebelum memulai perawatan?',
                'answer'   => 'Ya, konsultasi sangat disarankan agar terapis dapat menyesuaikan layanan dengan kebutuhan kulit, keluhan, dan hasil yang Anda inginkan.',
                'open'     => false,
            ],
            [
                'question' => 'Apakah ada waktu pemulihan atau efek samping setelah perawatan?',
                'answer'   => 'Sebagian besar perawatan tidak memerlukan waktu pemulihan. Anda mungkin mengalami kemerahan ringan atau sensitivitas setelah sesi perawatan, tetapi biasanya akan hilang dalam waktu singkat.',
                'open'     => true,
            ],
            [
                'question' => 'Apakah ruangan perawatan benar-benar private untuk muslimah?',
                'answer'   => 'Ya, Salon Dina Muslimah mengutamakan kenyamanan dan privasi tamu muslimah dengan area perawatan yang dibuat lebih tertutup dan aman.',
                'open'     => false,
            ],
            [
                'question' => 'Apakah saya bisa memilih jadwal dan cabang salon sendiri?',
                'answer'   => 'Bisa. Anda dapat memilih cabang, tanggal, dan jam perawatan yang tersedia melalui halaman booking sesuai kebutuhan Anda.',
                'open'     => false,
            ],
            [
                'question' => 'Apakah pembayaran bisa dilakukan setelah perawatan?',
                'answer'   => 'Bisa. Untuk metode pembayaran cash, pembayaran dilakukan setelah perawatan selesai di salon.',
                'open'     => false,
            ],
        ];
    }
}