<?php

use Illuminate\Support\Facades\Route;

// Halaman Utama (Gallery)
Route::get('/', function () {
    return view('user.gallery.gallery');
});


Route::get('/login', function () {
    return view('login.login');
});
// Halaman Service
Route::get('/signin', function () {
    return view('login.signin');
});
// Halaman Specialist
Route::get('/specialist', function () {
    return view('user.specialist.specialist');
});
// Halaman Gallery
Route::get('/gallery', function () {
    return view('user.gallery.gallery');
});

// Halaman Gallery
Route::get('/service', function () {
    return view('user.service.service');
});

Route::get('/sdetail', function () {
    return view('user.service.sdetail');
});

Route::get('/gallery/{slug}', function ($slug) {

    $galleries = [

        // ================= HAIR (3) =================
        'hair-repair-treatment' => [
            'title' => 'Hair Repair Treatment',
            'role' => 'hair',
            'before' => '/images/before.jpg',
            'after' => '/images/after.jpg',
            'before_list' => [
                'Rambut kering dan bercabang',
                'Tekstur kasar dan sulit diatur',
                'Rambut mudah patah',
            ],
            'after_list' => [
                'Rambut lebih halus',
                'Lebih kuat dan sehat',
                'Lebih mudah diatur',
            ],
        ],

        'hair-growth-therapy' => [
            'title' => 'Hair Growth Therapy',
            'role' => 'hair',
            'before' => '/images/before2.jpg',
            'after' => '/images/after2.jpg',
            'before_list' => [
                'Rambut tipis',
                'Pertumbuhan lambat',
            ],
            'after_list' => [
                'Rambut lebih tebal',
                'Pertumbuhan lebih cepat',
            ],
        ],

        'scalp-detox-hair-spa' => [
            'title' => 'Scalp Detox Hair Spa',
            'role' => 'hair',
            'before' => '/images/before3.jpg',
            'after' => '/images/after3.jpg',
            'before_list' => [
                'Kulit kepala berminyak',
                'Ketombe muncul',
            ],
            'after_list' => [
                'Kulit kepala bersih',
                'Lebih segar dan sehat',
            ],
        ],

        // ================= FACIAL (3) =================
        'acne-facial-treatment' => [
            'title' => 'Acne Facial Treatment',
            'role' => 'facial',
            'before' => '/images/before4.jpg',
            'after' => '/images/after4.jpg',
            'before_list' => [
                'Jerawat aktif',
                'Kulit berminyak',
            ],
            'after_list' => [
                'Jerawat berkurang',
                'Kulit lebih bersih',
            ],
        ],

        'brightening-facial' => [
            'title' => 'Brightening Facial',
            'role' => 'facial',
            'before' => '/images/before5.jpg',
            'after' => '/images/after5.jpg',
            'before_list' => [
                'Kulit kusam',
                'Warna tidak merata',
            ],
            'after_list' => [
                'Kulit lebih cerah',
                'Warna merata',
            ],
        ],

        'anti-aging-facial' => [
            'title' => 'Anti Aging Facial',
            'role' => 'facial',
            'before' => '/images/before6.jpg',
            'after' => '/images/after6.jpg',
            'before_list' => [
                'Garis halus terlihat',
                'Kulit mulai kendur',
            ],
            'after_list' => [
                'Kulit lebih kencang',
                'Kerutan berkurang',
            ],
        ],

        // ================= NAIL POLISH (3) =================
        'classic-nail-polish' => [
            'title' => 'Classic Nail Polish',
            'role' => 'nail polish',
            'before' => '/images/before7.jpg',
            'after' => '/images/after7.jpg',
            'before_list' => [
                'Kuku kusam',
                'Tidak terawat',
            ],
            'after_list' => [
                'Kuku lebih cantik',
                'Tampilan elegan',
            ],
        ],

        'gel-nail-polish' => [
            'title' => 'Gel Nail Polish',
            'role' => 'nail polish',
            'before' => '/images/before8.jpg',
            'after' => '/images/after8.jpg',
            'before_list' => [
                'Cat kuku mudah hilang',
            ],
            'after_list' => [
                'Tahan lama dan glossy',
            ],
        ],

        'nail-art-design' => [
            'title' => 'Nail Art Design',
            'role' => 'nail polish',
            'before' => '/images/before9.jpg',
            'after' => '/images/after9.jpg',
            'before_list' => [
                'Kuku polos',
            ],
            'after_list' => [
                'Desain modern dan unik',
            ],
        ],

        // ================= WAXING (3) =================
        'full-body-waxing' => [
            'title' => 'Full Body Waxing',
            'role' => 'waxing',
            'before' => '/images/before10.jpg',
            'after' => '/images/after10.jpg',
            'before_list' => [
                'Bulu tubuh lebat',
            ],
            'after_list' => [
                'Kulit halus dan bersih',
            ],
        ],

        'brazilian-waxing' => [
            'title' => 'Brazilian Waxing',
            'role' => 'waxing',
            'before' => '/images/before11.jpg',
            'after' => '/images/after11.jpg',
            'before_list' => [
                'Tidak nyaman di area sensitif',
            ],
            'after_list' => [
                'Lebih bersih dan rapi',
            ],
        ],

        'underarm-waxing' => [
            'title' => 'Underarm Waxing',
            'role' => 'waxing',
            'before' => '/images/before12.jpg',
            'after' => '/images/after12.jpg',
            'before_list' => [
                'Ketiak gelap dan berbulu',
            ],
            'after_list' => [
                'Lebih cerah dan halus',
            ],
        ],
    ];

    $gallery = $galleries[$slug] ?? abort(404);

    return view('user.gallery.gdetail', compact('gallery'));
});

Route::get('/specialist/{slug}', function ($slug) {

    $specialists = [
        'aisyah-rahmawati' => [
            'name' => 'Dr. Aisyah Rahmawati',
            'role' => 'Senior Beautician',
            'desc' => 'Specializing in facial treatments...',
            'img' => 'https://via.placeholder.com/400x300',
            'services' => ['Facial Treatment', 'Skin Brightening', 'Acne Care']
        ],
        'kevin-pratama' => [
            'name' => 'Dr. Kevin Pratama',
            'role' => 'Skin Specialist',
            'desc' => 'Expert in advanced dermatology...',
            'img' => 'https://via.placeholder.com/400x300',
            'services' => ['Anti Aging', 'Dermatology', 'Laser Therapy']
        ],
    ];

    $specialist = $specialists[$slug] ?? abort(404);

    return view('user.specialist.spdetail', compact('specialist'));
});