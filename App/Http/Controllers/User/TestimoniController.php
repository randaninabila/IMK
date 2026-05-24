<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TestimoniController extends Controller
{
    public function index()
    {
        $testimonials = Schema::hasTable('ulasan')
            ? $this->getAcceptedTestimonials()
            : collect();

        $faqs = $this->getFaqs();

        return view('user.testimoni.testimoni', compact('testimonials', 'faqs'));
    }

    private function getAcceptedTestimonials()
    {
        $query = DB::table('ulasan as u');

        $joinedPelanggan = false;
        $joinedUsers = false;

        if (
            Schema::hasTable('pelanggan') &&
            Schema::hasColumn('ulasan', 'pelanggan_id') &&
            Schema::hasColumn('pelanggan', 'pelanggan_id')
        ) {
            $query->leftJoin('pelanggan as p', 'p.pelanggan_id', '=', 'u.pelanggan_id');
            $joinedPelanggan = true;

            if (
                Schema::hasTable('users') &&
                Schema::hasColumn('pelanggan', 'user_id') &&
                Schema::hasColumn('users', 'user_id')
            ) {
                $query->leftJoin('users as us', 'us.user_id', '=', 'p.user_id');
                $joinedUsers = true;
            }
        }

        $serviceExpression = "'Ulasan pelanggan'";

        if (
            Schema::hasColumn('ulasan', 'booking_id') &&
            Schema::hasTable('booking_detail') &&
            Schema::hasColumn('booking_detail', 'booking_id') &&
            Schema::hasColumn('booking_detail', 'layanan_cabang_id') &&
            Schema::hasTable('layanan_cabang') &&
            Schema::hasColumn('layanan_cabang', 'layanan_cabang_id') &&
            Schema::hasColumn('layanan_cabang', 'layanan_id') &&
            Schema::hasTable('layanan') &&
            Schema::hasColumn('layanan', 'layanan_id') &&
            Schema::hasColumn('layanan', 'nama_layanan')
        ) {
            $query->leftJoin('booking_detail as bd', 'bd.booking_id', '=', 'u.booking_id')
                ->leftJoin('layanan_cabang as lc', 'lc.layanan_cabang_id', '=', 'bd.layanan_cabang_id')
                ->leftJoin('layanan as l', 'l.layanan_id', '=', 'lc.layanan_id');

            $serviceExpression = "COALESCE(GROUP_CONCAT(DISTINCT l.nama_layanan ORDER BY l.nama_layanan SEPARATOR ', '), 'Ulasan pelanggan')";
        }

        if (Schema::hasTable('ulasan_foto') && Schema::hasColumn('ulasan_foto', 'ulasan_id')) {
            $fotoColumn = $this->firstColumn('ulasan_foto', [
                'foto_path',
                'path',
                'foto',
                'gambar',
                'url_foto',
                'nama_file',
                'foto_ulasan',
            ]);

            if ($fotoColumn) {
                $fotoSubQuery = DB::table('ulasan_foto')
                    ->select(
                        'ulasan_id',
                        DB::raw("MIN($fotoColumn) as foto")
                    )
                    ->groupBy('ulasan_id');

                $query->leftJoinSub($fotoSubQuery, 'uf', function ($join) {
                    $join->on('uf.ulasan_id', '=', 'u.ulasan_id');
                });
            }
        }

        $statusColumn = $this->firstColumn('ulasan', [
            'status',
            'status_ulasan',
            'moderasi',
        ]);

        if ($statusColumn) {
            $query->whereIn("u.$statusColumn", [
                'diterima',
                'approved',
                'accepted',
                'aktif',
                'published',
            ]);
        }

        $idColumn = $this->firstColumn('ulasan', [
            'ulasan_id',
            'id',
        ]);

        $commentColumn = $this->firstColumn('ulasan', [
            'komentar',
            'isi_ulasan',
            'ulasan',
            'saran',
            'pesan',
        ]);

        $ratingColumn = $this->firstColumn('ulasan', [
            'rating',
            'bintang',
            'nilai',
        ]);

        $dateColumn = $this->firstColumn('ulasan', [
            'created_at',
            'tanggal_ulasan',
            'tanggal',
        ]);

        $nameParts = [];

        if ($joinedUsers && Schema::hasColumn('users', 'nama')) {
            $nameParts[] = 'MAX(us.nama)';
        }

        if ($joinedPelanggan && Schema::hasColumn('pelanggan', 'nama')) {
            $nameParts[] = 'MAX(p.nama)';
        }

        if ($joinedPelanggan && Schema::hasColumn('pelanggan', 'nama_pelanggan')) {
            $nameParts[] = 'MAX(p.nama_pelanggan)';
        }

        if (Schema::hasColumn('ulasan', 'nama')) {
            $nameParts[] = 'MAX(u.nama)';
        }

        if (Schema::hasColumn('ulasan', 'nama_pelanggan')) {
            $nameParts[] = 'MAX(u.nama_pelanggan)';
        }

        $nameExpression = count($nameParts) > 0
            ? 'COALESCE(' . implode(', ', $nameParts) . ", 'Pelanggan Dina')"
            : "'Pelanggan Dina'";

        $commentExpression = $commentColumn
            ? "COALESCE(MAX(u.$commentColumn), 'Ulasan pelanggan belum tersedia.')"
            : "'Ulasan pelanggan belum tersedia.'";

        $ratingExpression = $ratingColumn
            ? "COALESCE(MAX(u.$ratingColumn), 5)"
            : "5";

        $dateExpression = $dateColumn
            ? "MAX(u.$dateColumn)"
            : "NULL";

        $photoExpression = isset($fotoColumn)
            ? "MAX(uf.foto)"
            : "NULL";

        $selects = [
            DB::raw($idColumn ? "u.$idColumn as review_id" : "0 as review_id"),
            DB::raw("$nameExpression as name"),
            DB::raw("$commentExpression as comment"),
            DB::raw("$ratingExpression as rating"),
            DB::raw("$serviceExpression as service"),
            DB::raw("$photoExpression as photo"),
            DB::raw("$dateExpression as review_date"),
        ];

        $query->select($selects);

        if ($idColumn) {
            $query->groupBy("u.$idColumn");
        }

        if ($dateColumn) {
            $query->orderByDesc("u.$dateColumn");
        } elseif ($idColumn) {
            $query->orderByDesc("u.$idColumn");
        }

        return $query
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name ?: 'Pelanggan Dina',
                    'comment' => $item->comment ?: 'Ulasan pelanggan belum tersedia.',
                    'rating' => (int) ($item->rating ?: 5),
                    'service' => $item->service ?: 'Ulasan pelanggan',
                    'photo' => $this->formatPhotoUrl($item->photo ?? null),
                    'date' => $item->review_date ?? null,
                ];
            });
    }

    private function firstColumn(string $table, array $columns): ?string
    {
        foreach ($columns as $column) {
            if (Schema::hasColumn($table, $column)) {
                return $column;
            }
        }

        return null;
    }

    private function formatPhotoUrl(?string $photo): ?string
    {
        if (!$photo) {
            return null;
        }

        if (
            str_starts_with($photo, 'http://') ||
            str_starts_with($photo, 'https://') ||
            str_starts_with($photo, 'data:')
        ) {
            return $photo;
        }

        if (str_starts_with($photo, 'storage/')) {
            return asset($photo);
        }

        return asset('storage/' . $photo);
    }

    private function getFaqs(): array
    {
        return [
            [
                'question' => 'Apakah perawatan ini aman dan cocok untuk semua jenis kulit?',
                'answer' => 'Sebagian besar perawatan aman untuk berbagai jenis kulit. Namun, kondisi kulit setiap orang berbeda, sehingga kami tetap menyarankan konsultasi singkat sebelum perawatan dimulai.',
                'open' => false,
            ],
            [
                'question' => 'Apakah saya perlu konsultasi sebelum memulai perawatan?',
                'answer' => 'Ya, konsultasi sangat disarankan agar terapis dapat menyesuaikan layanan dengan kebutuhan kulit, keluhan, dan hasil yang Anda inginkan.',
                'open' => false,
            ],
            [
                'question' => 'Apakah ada waktu pemulihan atau efek samping setelah perawatan?',
                'answer' => 'Sebagian besar perawatan tidak memerlukan waktu pemulihan. Anda mungkin mengalami kemerahan ringan atau sensitivitas setelah sesi perawatan, tetapi biasanya akan hilang dalam waktu singkat.',
                'open' => true,
            ],
            [
                'question' => 'Apakah ruangan perawatan benar-benar private untuk muslimah?',
                'answer' => 'Ya, Salon Dina Muslimah mengutamakan kenyamanan dan privasi tamu muslimah dengan area perawatan yang dibuat lebih tertutup dan aman.',
                'open' => false,
            ],
            [
                'question' => 'Apakah saya bisa memilih jadwal dan cabang salon sendiri?',
                'answer' => 'Bisa. Anda dapat memilih cabang, tanggal, dan jam perawatan yang tersedia melalui halaman booking sesuai kebutuhan Anda.',
                'open' => false,
            ],
            [
                'question' => 'Apakah pembayaran bisa dilakukan setelah perawatan?',
                'answer' => 'Bisa. Untuk metode pembayaran cash, pembayaran dilakukan setelah perawatan selesai di salon.',
                'open' => false,
            ],
        ];
    }
}