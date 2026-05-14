<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// =====================
// PUBLIC / USER
// =====================

// Home
Route::get('/', function () {
    return view('user.gallery.gallery');
});

// Login & Register
Route::middleware('guest')->group(function () {

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/signin', function () {
        return view('auth.signin');
    })->name('signin');

});

// Service
Route::get('/service', function () {
    return view('user.service.service');
});

Route::get('/sdetail', function () {
    return view('user.service.sdetail');
});

// Specialist
Route::get('/specialist', function () {
    return view('user.specialist.specialist');
});

// Gallery
Route::get('/gallery', function () {
    return view('user.gallery.gallery');
});

// =====================
// GALLERY DETAIL
// =====================

Route::get('/gallery/{slug}', function ($slug) {

    $galleries = [

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

        'acne-facial-treatment' => [
            'title' => 'Acne Facial Treatment',
            'role' => 'facial',
            'before' => '/images/before4.jpg',
            'after' => '/images/after4.jpg',
        ],

    ];

    $gallery = $galleries[$slug] ?? abort(404);

    return view('user.gallery.gdetail', compact('gallery'));

})->name('gallery.detail');

// =====================
// SPECIALIST DETAIL
// =====================

Route::get('/specialist/{slug}', function ($slug) {

    $specialists = [

        'aisyah-rahmawati' => [
            'name' => 'Dr. Aisyah Rahmawati',
            'role' => 'Senior Beautician',
            'desc' => 'Specializing in facial treatments...',
            'img' => 'https://via.placeholder.com/400x300',
            'services' => [
                'Facial Treatment',
                'Skin Brightening',
                'Acne Care'
            ]
        ],

        'kevin-pratama' => [
            'name' => 'Dr. Kevin Pratama',
            'role' => 'Skin Specialist',
            'desc' => 'Expert in advanced dermatology...',
            'img' => 'https://via.placeholder.com/400x300',
            'services' => [
                'Anti Aging',
                'Dermatology',
                'Laser Therapy'
            ]
        ],

    ];

    $specialist = $specialists[$slug] ?? abort(404);

    return view('user.specialist.spdetail', compact('specialist'));

})->name('specialist.detail');

// =====================
// AUTH
// =====================

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// =====================
// QUICK VERIFY DEV
// =====================

Route::post('/fake-verify-email', function () {

    $user = auth()->user();

    $user->email_verified_at = now();

    $user->save();

    return redirect()->intended('/');

})->middleware('auth');

// =====================
// EMAIL VERIFICATION
// =====================

Route::middleware('auth')->group(function () {

    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {

        $request->fulfill();

        return redirect('/');

    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {

        $request->user()->sendEmailVerificationNotification();

        return back();

    })->middleware('throttle:6,1')->name('verification.send');

});

// =====================
// OWNER
// =====================

Route::middleware(['auth', 'role:owner'])->group(function () {

    Route::get('/dashboard', function () {
        return view('owner.dashboard');
    });

    Route::get('/customers', function () {
        return view('owner.customers');
    });

    Route::get('/serviceo', function () {
        return view('owner.service.service');
    });

    Route::get('/employee', function () {
        return view('owner.employees.employee');
    });

    Route::get('/eemployee', function () {
        return view('owner.employees.eemployee');
    })->name('employee.edit');

    Route::get('/aemployee', function () {
        return view('owner.employees.aemployee');
    });

    Route::get('/eservice', function () {
        return view('owner.service.eservice');
    });

});

// =====================
// ADMIN
// =====================

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

    });

// =====================
// PEGAWAI
// =====================

// Route::middleware(['auth', 'role:pegawai'])
//     ->prefix('pegawai')
//     ->name('pegawai.')
//     ->group(function () {

//         Route::get('/dashboard', function () {
//             return view('pegawai.dashboard');
//         })->name('dashboard');

//     });

Route::get('/udin', function () {
        return view('pegawai.dashboard');
    });
    Route::get('/his1', function () {
        return view('pegawai.history.his1');
    });
Route::get('/not1', function () {
        return view('pegawai.notifikasi.not1');
    });
Route::get('/prof1', function () {
        return view('pegawai.profile.prof1');
    });
Route::get('/jkb', function () {
        return view('pegawai.jk.jkb');
    });
Route::get('/book1', function () {
        return view('pegawai.booking.book1');
    });
// =====================
// PELANGGAN
// =====================

Route::middleware(['auth', 'role:pelanggan'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {

        Route::get('/profile', function () {
            return view('pelanggan.profile');
        })->name('profile');

        Route::get('/bookings', function () {
            return view('pelanggan.bookings');
        })->name('bookings');

    });