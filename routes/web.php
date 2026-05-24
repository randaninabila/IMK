<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Album;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\ServiceController;
use App\Http\Controllers\Owner\EmployeeController;
use App\Http\Controllers\Owner\CustomerController;

use App\Http\Controllers\User\GalleryController;
use App\Http\Controllers\User\ServiceDetailController;
use App\Http\Controllers\User\SpecialistController;
use App\Http\Controllers\User\LayananDetailController;
use App\Http\Controllers\User\ProfileController;

use App\Http\Controllers\Pegawai\PegawaiDashboardController;
use App\Http\Controllers\Pegawai\JadwalPegawaiController;
use App\Http\Controllers\Pegawai\PBookingController;
use App\Http\Controllers\Pegawai\PProfileController;
use App\Http\Controllers\NotifikasiController;

// =====================
// PUBLIC / USER
// =====================

// Home
Route::get('/', [GalleryController::class, 'index']);


// Login & Register
Route::middleware('guest')->group(function () {

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

Route::get('/forgotpw', function () {
        return view('login.forgotpw');
    });

Route::get('/verif', function () {
        return view('login.verif');
    });

Route::get('/newpw', function () {
        return view('login.newpw');
    });


// FORM FORGOT PASSWORD
Route::get('/forgot-password', function () {
    return view('login.forgotpw');
})->name('password.request');

// KIRIM LINK RESET
Route::post('/forgot-password', function (Request $request) {

    $request->validate([
        'email' => 'required|email'
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

// FORM RESET PASSWORD
Route::get('/reset-password/{token}', function (string $token) {
    return view('login.newpw', ['token' => $token]);
})->name('password.reset');

// UPDATE PASSWORD
Route::post('/reset-password', function (Request $request) {

    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        ),

        function ($user, $password) {

            $user->password = Hash::make($password);
            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect('/login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->name('password.update');


// Service
Route::get('/service', function () {
    return view('user.service.service');
});

// Service list
Route::get('/service', [ServiceDetailController::class, 'index']);

// Service detail
Route::get('/service/{jenis_layanan_id}', [ServiceDetailController::class, 'show'])
    ->name('service.detail')
    ->whereNumber('jenis_layanan_id');

Route::get('/specialist', [SpecialistController::class, 'index']);
 
Route::get('/service/layanan/{layanan_id}', [LayananDetailController::class, 'show'])
    ->name('service.layanan');

// Detail specialist
Route::get('/specialist/{pegawai_id}', [SpecialistController::class, 'show'])
    ->name('specialist.show')
    ->whereNumber('pegawai_id');

// =====================
// GALLERY DETAIL
// =====================
Route::get('/gallery', [GalleryController::class, 'index'])
    ->name('gallery.index');

Route::get('/gallery/{slug}', [GalleryController::class, 'show'])
    ->name('gallery.detail');

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
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// =====================
// QUICK VERIFY DEV
// =====================
Route::post('/fake-verify-email', function () {
    $user = auth()->user();
    $user->email_verified_at = now();

    $user->save();
    return redirect()->intended('/');
})->middleware('auth');

Route::get('/logout-test', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
});

// =====================
// EMAIL VERIFICATION
// =====================
Route::middleware('auth')->group(function () {

    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/verify-email-notice', [AuthController::class, 'verifyEmailNotice'])
        ->middleware('auth')
        ->name('verification.notice');

    Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])
        ->name('verification.verify');

    Route::post('/resend-verification', [AuthController::class, 'resendVerification'])
        ->middleware('auth')
        ->name('verification.resend');
});

// =====================
// OWNER
// =====================
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('owner.dashboard');
    Route::get('/export-pdf', [DashboardController::class, 'exportPDF'])->name('owner.export-pdf');
    
    Route::get('/serviceo', [ServiceController::class, 'index'])->name('owner.service');
    Route::get('/serviceo/edit', [ServiceController::class, 'edit'])->name('owner.service.edit');
    
    Route::get('/employee', [EmployeeController::class, 'index'])->name('owner.employee');
    Route::get('/employee/edit', [EmployeeController::class, 'edit'])->name('owner.employee.edit');
    
    Route::post('/employee/store', [EmployeeController::class, 'store'])->name('owner.employee.store');
    Route::patch('/employee/{pegawai_id}/today-status', [EmployeeController::class, 'updateTodayStatus'])->name('owner.employee.today-status');
    Route::patch('/employee/{pegawai_id}/role', [EmployeeController::class, 'updateRole'])->name('owner.employee.role');
    Route::patch('/employee/{pegawai_id}/resign', [EmployeeController::class, 'resign'])->name('owner.employee.resign');
    Route::get('/employee/{pegawai_id}/edit', [EmployeeController::class, 'editEmployee'])->name('owner.employee.edit-form');
    Route::patch('/employee/{pegawai_id}', [EmployeeController::class, 'updateEmployee'])->name('owner.employee.update');

    Route::get('/customers', [CustomerController::class, 'index'])->name('owner.customer');
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

Route::middleware(['auth', 'role:pegawai'])
    ->prefix('pegawai')
    ->name('pegawai.')
    ->group(function () {

        Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])
        ->name('dashboard');

        Route::get('/pegawai/history', [PBookingController::class, 'history'])
        ->name('history')
        ->middleware('auth');

    });
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
Route::get('/prof2', function () {
        return view('pegawai.profile.prof2');
    });

Route::get('/jkb', function () {
        return view('pegawai.jk.jkb');
    });
Route::get('/book1', function () {
        return view('pegawai.booking.book1');

        // routes/web.php
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])
        ->name('notifikasi');

        Route::put('/notifikasi/{id}/dibaca', [NotifikasiController::class, 'markAsRead'])
        ->name('notifikasi.dibaca');

        Route::post('/notifikasi/{id}/dismiss', [NotifikasiController::class, 'dismiss'])->name('notifikasi.dismiss');

        Route::get('/notifikasi/{id}/dibaca', function () {
        return redirect()->route('pegawai.notifikasi');
        });

        Route::get('/profile', [PProfileController::class, 'index'])->name('profile');
        Route::get('/profile/edit', [PProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [PProfileController::class, 'update'])->name('profile.update');

        Route::get('/jadwal', [JadwalPegawaiController::class, 'index'])
        ->name('jadwal-kerja');

        Route::get('/pegawai/booking', [PBookingController::class, 'index'])
        ->name('booking');

        // routes/web.php
// Ganti Route::post menjadi Route::match agar terima POST & PATCH
Route::match(['post', 'patch'], '/booking/{booking}/update-status', [
    PBookingController::class, 'updateStatus'
])->name('booking.updateStatus');
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

// =====================
// PROFILE
// =====================
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');