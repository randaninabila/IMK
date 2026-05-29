<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\HomeController; 

use App\Http\Controllers\Pegawai\PegawaiDashboardController;
use App\Http\Controllers\Pegawai\JadwalPegawaiController;
use App\Http\Controllers\Pegawai\PBookingController;
use App\Http\Controllers\Pegawai\PProfileController;
use App\Http\Controllers\NotifikasiController;

use App\Http\Controllers\ForgotPasswordController;


// =====================
// PUBLIC / USER
// =====================

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

// STEP 1 — Forgot password form + send OTP
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
    ->name('password.request');
 
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])
    ->name('password.send-otp');
 
// STEP 2 — OTP verification form + verify + resend
Route::get('/verify-otp', [ForgotPasswordController::class, 'showOtpForm'])
    ->name('password.otp');
 
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])
    ->name('password.verify-otp');
 
Route::post('/resend-otp', [ForgotPasswordController::class, 'resendOtp'])
    ->name('password.resend-otp');
 
// STEP 3 — New password form + update
Route::get('/reset-password', [ForgotPasswordController::class, 'showNewPasswordForm'])
    ->name('password.reset.form');
 
Route::post('/reset-password', [ForgotPasswordController::class, 'updatePassword'])
    ->name('password.update');

// Service list
Route::get('/service', [ServiceDetailController::class, 'index']);

Route::get('/service/{jenis_layanan_id}', [ServiceDetailController::class, 'show'])
    ->name('service.detail')
    ->whereNumber('jenis_layanan_id');

Route::get('/specialist', [SpecialistController::class, 'index']);

Route::get('/service/layanan/{layanan_id}', [LayananDetailController::class, 'show'])
    ->name('service.layanan');

Route::get('/specialist/{pegawai_id}', [SpecialistController::class, 'show'])
    ->name('specialist.show')
    ->whereNumber('pegawai_id');

// =====================
// GALLERY
// =====================
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{slug}', [GalleryController::class, 'show'])->name('gallery.detail');

// =====================
// SPECIALIST DETAIL
// =====================
Route::get('/specialist/{slug}', function ($slug) {
    $specialists = [
        'aisyah-rahmawati' => [
            'name'     => 'Dr. Aisyah Rahmawati',
            'role'     => 'Senior Beautician',
            'desc'     => 'Specializing in facial treatments...',
            'img'      => 'https://via.placeholder.com/400x300',
            'services' => ['Facial Treatment', 'Skin Brightening', 'Acne Care'],
        ],
        'kevin-pratama' => [
            'name'     => 'Dr. Kevin Pratama',
            'role'     => 'Skin Specialist',
            'desc'     => 'Expert in advanced dermatology...',
            'img'      => 'https://via.placeholder.com/400x300',
            'services' => ['Anti Aging', 'Dermatology', 'Laser Therapy'],
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

    Route::get('/verify-email/{otp}', [AuthController::class, 'verifyEmail'])
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

        // Dashboard
        Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/pegawai/history', [PBookingController::class, 'history'])
        ->name('history');

        Route::get('/notifikasi', [NotifikasiController::class, 'index'])
        ->name('notifikasi');
    
        Route::put('/notifikasi/{id}/dibaca', [NotifikasiController::class, 'markAsRead'])
        ->name('notifikasi.dibaca');
    
        Route::post('/notifikasi/{id}/dismiss', [NotifikasiController::class, 'dismiss'])->name('notifikasi.dismiss');
    
        Route::get('/notifikasi/{id}/dibaca', function () {
            return redirect()->route('pegawai.notifikasi');
        });

    
        Route::get('/profile', [PProfileController::class, 'index'])->name('profile');
        Route::put('/profile/update', [PProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password-update', [PProfileController::class, 'updatePassword'])->name('profile.password');

        Route::get('/jadwal', [JadwalPegawaiController::class, 'index'])
        ->name('jadwal-kerja');
    
        Route::get('/pegawai/booking', [PBookingController::class, 'index'])
        ->name('booking');
        Route::post('/booking/{booking_id}/update-status', [PBookingController::class, 'updateStatus'
        ])->name('booking.updateStatus');
    });


        // routes/web.php
// Ganti Route::post menjadi Route::match agar terima POST & PATCH
Route::match(['post', 'patch'], '/booking/{booking}/update-status', [
    PBookingController::class, 'updateStatus'
])->name('booking.updateStatus');
    

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

        Route::get('/booking/create/{layanan_cabang_id}', [BookingController::class, 'create'])
            ->name('booking.create');

        Route::post('/booking/store', [BookingController::class, 'store'])
            ->name('booking.store');

        Route::get('/payment/{booking_id}', [PaymentController::class, 'show'])
            ->name('payment.show');
        
        Route::post('/payment/{booking_id}/process', [PaymentController::class, 'process'])
            ->name('payment.process');
        
        Route::get('/payment/{booking_id}/success', [PaymentController::class, 'success'])
            ->name('payment.success');

        Route::get('/bookings', [App\Http\Controllers\User\BookingController::class, 'history'])
            ->name('bookings');
            
        Route::get('/booking/{booking_id}', [App\Http\Controllers\User\BookingController::class, 'show'])
            ->name('booking.show');
        
        Route::get('/booking/{booking_id}/reschedule', [App\Http\Controllers\User\BookingController::class, 'showReschedule'])
            ->name('booking.reschedule');
        
        Route::post('/booking/{booking_id}/reschedule', [App\Http\Controllers\User\BookingController::class, 'processReschedule'])
            ->name('booking.reschedule.process');
        });

// =====================
// PROFILE
// =====================
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

