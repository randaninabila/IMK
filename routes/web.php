<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Models\Album;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;

use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\ServiceController;
use App\Http\Controllers\Owner\ManageServiceController;
use App\Http\Controllers\Owner\EmployeeController;
use App\Http\Controllers\Owner\CustomerController;

use App\Http\Controllers\User\ServiceDetailController;
use App\Http\Controllers\User\SpecialistController;
use App\Http\Controllers\User\LayananDetailController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\GalleryController;
use App\Http\Controllers\User\TestimoniController;
use App\Http\Controllers\User\UlasanController;
use App\Http\Controllers\User\PromoController;

use App\Http\Controllers\Pegawai\PegawaiDashboardController;
use App\Http\Controllers\Pegawai\JadwalPegawaiController;
use App\Http\Controllers\Pegawai\PBookingController;
use App\Http\Controllers\Pegawai\PProfileController;
use App\Http\Controllers\NotifikasiController;

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\PenjadwalanAdminController;
use App\Http\Controllers\Admin\PegawaiAdminController;
use App\Http\Controllers\Admin\PelangganAdminController;
use App\Http\Controllers\Admin\UlasanAdminController;
use App\Http\Controllers\Admin\PengaturanAdminController;
use App\Http\Controllers\Admin\InputPromoAdminController;


// =====================
// PUBLIC / USER
// =====================

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/booking', function () {
    return view('user.booking.booking');
})->name('booking');

Route::get('/schedule', function () {
    return view('user.schedule.schedule');
})->name('schedule');

Route::get('/time', function () {
    return view('user.time.time');
})->name('time');

Route::get('/details', function () {
    return view('user.detail.detail');
})->name('details');

Route::get('/payment', function () {
    return view('user.payment.payment');
})->name('payment');

Route::get('/confirmation', function () {
    return view('user.confirmation.confirmation');
})->name('confirmation');

Route::get('/contactsalon', function () {
    return view('user.contactsalon.contactsalon');
})->name('contactsalon');

Route::get('/testimoni', [TestimoniController::class, 'index'])
    ->name('testimoni');

Route::get('/ulasan/inputulasan', function () {
    return view('user.ulasan.inputulasan');
})->name('ulasan.inputulasan');


// =====================
// GALLERY
// =====================
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

Route::get('/gallery-index', function () {
    return redirect()->route('gallery');
})->name('gallery.index');

Route::get('/gallery/detail/{slug}', [GalleryController::class, 'show'])->name('gallery.detail');


// =====================
// LOGIN & REGISTER
// =====================

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

// Verifikasi email via OTP
Route::get('/email/verify',              [AuthController::class, 'verifyEmailNotice'])->name('verification.notice');
Route::post('/email/verify/otp',         [AuthController::class, 'verifyEmailOtp'])->name('verification.verify-otp');
Route::post('/email/verify/resend',      [AuthController::class, 'resendVerification'])->middleware('auth')->name('verification.resend');
Route::post('/email/verify/resend-guest',[AuthController::class, 'resendVerificationGuest'])->name('verification.resend-guest');


// =====================
// FORGOT PASSWORD
// =====================

Route::get('/forgotpw', function () {
    return view('login.forgotpw');
});

Route::get('/verif', function () {
    return view('login.verif');
});

Route::get('/newpw', function () {
    return view('login.newpw');
});

if (class_exists(ForgotPasswordController::class)) {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])
        ->name('password.send-otp');

    Route::get('/verify-otp', [ForgotPasswordController::class, 'showOtpForm'])
        ->name('password.otp');

    Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])
        ->name('password.verify-otp');

    Route::post('/resend-otp', [ForgotPasswordController::class, 'resendOtp'])
        ->name('password.resend-otp');

    Route::get('/reset-password', [ForgotPasswordController::class, 'showNewPasswordForm'])
        ->name('password.reset.form');

    Route::post('/reset-password', [ForgotPasswordController::class, 'updatePassword'])
        ->name('password.update');
} else {
    Route::get('/forgot-password', function () {
        return view('login.forgotpw');
    })->name('password.request');

    Route::post('/forgot-password', function (Request $request) {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    })->name('password.email');

    Route::get('/reset-password/{token}', function (string $token) {
        return view('login.newpw', ['token' => $token]);
    })->name('password.reset');

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
}

Route::post('/forgot-password-link', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

Route::get('/reset-password-link/{token}', function (string $token) {
    return view('login.newpw', ['token' => $token]);
})->name('password.reset.link');


// =====================
// SERVICE
// =====================

Route::get('/service', [ServiceDetailController::class, 'index'])
    ->name('service');

Route::get('/service/layanan/{layanan_id}', [LayananDetailController::class, 'show'])
    ->name('service.layanan')
    ->whereNumber('layanan_id');

Route::get('/service/{jenis_layanan_id}/paket/{paket_id}', [ServiceDetailController::class, 'showPaketDetail'])
    ->name('service.paket.detail')
    ->whereNumber(['jenis_layanan_id', 'paket_id']);

Route::get('/service/{jenis_layanan_id}', [ServiceDetailController::class, 'show'])
    ->name('service.detail')
    ->whereNumber('jenis_layanan_id');


// =====================
// SPECIALIST
// =====================

Route::get('/specialist', [SpecialistController::class, 'index'])
    ->name('specialist');

Route::get('/specialist/{pegawai_id}', [SpecialistController::class, 'show'])
    ->name('specialist.show')
    ->whereNumber('pegawai_id');

Route::get('/specialist/detail/{slug}', function ($slug) {
    $specialists = [
        'aisyah-rahmawati' => [
            'name' => 'Dr. Aisyah Rahmawati',
            'role' => 'Senior Beautician',
            'desc' => 'Specializing in facial treatments...',
            'img' => 'https://via.placeholder.com/400x300',
            'services' => [
                'Facial Treatment',
                'Skin Brightening',
                'Acne Care',
            ],
        ],
        'kevin-pratama' => [
            'name' => 'Dr. Kevin Pratama',
            'role' => 'Skin Specialist',
            'desc' => 'Expert in advanced dermatology...',
            'img' => 'https://via.placeholder.com/400x300',
            'services' => [
                'Anti Aging',
                'Dermatology',
                'Laser Therapy',
            ],
        ],
    ];

    $specialist = $specialists[$slug] ?? abort(404);

    return view('user.specialist.spdetail', compact('specialist'));
})->name('specialist.detail');

Route::get('/specialist/slug/{slug}', function ($slug) {
    return redirect()->route('specialist.detail', ['slug' => $slug]);
})->name('specialist.detail.slug');


// =====================
// AUTH
// =====================

Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::post('/fake-verify-email', function () {
    $user = auth()->user();

    if ($user) {
        $user->email_verified_at = now();
        $user->save();
    }

    return redirect()->intended('/');
})->middleware('auth');

Route::get('/logout-test', function () {
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
});


// =====================
// OWNER
// =====================

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('owner.dashboard');

    Route::get('/export-pdf', [DashboardController::class, 'exportPDF'])
        ->name('owner.export-pdf');

    Route::get('/serviceo', [ServiceController::class, 'index'])
        ->name('owner.service');

    Route::get('/serviceo/edit', [ServiceController::class, 'edit'])
        ->name('owner.service.edit');

    Route::get('/service/manage', [ManageServiceController::class, 'index'])
        ->name('owner.service.manage');

    Route::post('/service/manage/store', [ManageServiceController::class, 'store'])
        ->name('owner.service.store');

    Route::put('/service/manage/{id}', [ManageServiceController::class, 'update']
    )->name('owner.service.update');

    Route::patch('/service/manage/{id}/deactivate', [ManageServiceController::class, 'deactivate']
    )->name('owner.service.deactivate');

    Route::patch('/service/manage/{id}/activate', [ManageServiceController::class, 'activate'])
        ->name('owner.service.activate');

    Route::post('/service/manage/jenis', [ManageServiceController::class, 'storeJenis']
    )->name('owner.service.jenis.store');

    Route::post('/service/manage/paket', [ManageServiceController::class, 'storePaket']
    )->name('owner.service.paket.store');

    Route::put('/service/manage/paket/{id}', [ManageServiceController::class, 'updatePaket'])
        ->name('owner.service.paket.update');

    Route::get('/employee', [EmployeeController::class, 'index'])
        ->name('owner.employee');

    Route::get('/employee/edit', [EmployeeController::class, 'edit'])
        ->name('owner.employee.edit');

    Route::post('/employee/store', [EmployeeController::class, 'store'])
        ->name('owner.employee.store');

    Route::patch('/employee/{pegawai_id}/today-status', [EmployeeController::class, 'updateTodayStatus'])
        ->name('owner.employee.today-status');

    Route::patch('/employee/{pegawai_id}/role', [EmployeeController::class, 'updateRole'])
        ->name('owner.employee.role');

    Route::patch('/employee/{pegawai_id}/resign', [EmployeeController::class, 'resign'])
        ->name('owner.employee.resign');

    Route::get('/employee/{pegawai_id}/edit', [EmployeeController::class, 'editEmployee'])
        ->name('owner.employee.edit-form');

    Route::patch('/employee/{pegawai_id}', [EmployeeController::class, 'updateEmployee'])
        ->name('owner.employee.update');

    Route::get('/customers', [CustomerController::class, 'index'])
        ->name('owner.customer');
});


// =====================
// ADMIN
// =====================

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // ... route lain tetap ...

        Route::post('/penjadwalan/booking/store', [PenjadwalanAdminController::class, 'storeBooking'])
            ->name('penjadwalan.booking.store');

        // TAMBAHAN — route update booking (edit)
        Route::put('/penjadwalan/booking/{booking_id}', [PenjadwalanAdminController::class, 'updateBooking'])
            ->name('penjadwalan.booking.update');

        Route::put('/penjadwalan/booking/{booking_id}/status', [PenjadwalanAdminController::class, 'updateBookingStatus'])
            ->name('penjadwalan.booking.status');

        Route::delete('/penjadwalan/booking/{booking_id}', [PenjadwalanAdminController::class, 'cancelBooking'])
            ->name('penjadwalan.booking.cancel');
    });

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])
            ->name('dashboard');

        Route::get('/penjadwalan', [PenjadwalanAdminController::class, 'index'])
            ->name('penjadwalan');

        Route::post('/penjadwalan/booking/store', [PenjadwalanAdminController::class, 'storeBooking'])
            ->name('penjadwalan.booking.store');

        Route::put('/penjadwalan/booking/{booking_id}/status', [PenjadwalanAdminController::class, 'updateBookingStatus'])
            ->name('penjadwalan.booking.status');

        Route::delete('/penjadwalan/booking/{booking_id}', [PenjadwalanAdminController::class, 'cancelBooking'])
            ->name('penjadwalan.booking.cancel');

        Route::get('/pegawai', [PegawaiAdminController::class, 'index'])
            ->name('pegawai');

        Route::get('/staff', function () {
            return redirect()->route('admin.pegawai');
        })->name('staff');

        Route::post('/pegawai/store', [PegawaiAdminController::class, 'store'])
            ->name('pegawai.store');

        Route::put('/pegawai/{pegawai_id}', [PegawaiAdminController::class, 'update'])
            ->name('pegawai.update');

        Route::delete('/pegawai/{pegawai_id}', [PegawaiAdminController::class, 'destroy'])
            ->name('pegawai.destroy');

        Route::get('/pelanggan', [PelangganAdminController::class, 'index'])
            ->name('pelanggan');

        Route::post('/pelanggan', [PelangganAdminController::class, 'store'])
            ->name('pelanggan.store');

        Route::put('/pelanggan/{pelanggan_id}', [PelangganAdminController::class, 'update'])
            ->name('pelanggan.update');

        Route::delete('/pelanggan/{pelanggan_id}', [PelangganAdminController::class, 'destroy'])
            ->name('pelanggan.destroy');

        Route::get('/pengaturan', [PengaturanAdminController::class, 'index'])
            ->name('pengaturan');

        Route::put('/pengaturan/profile', [PengaturanAdminController::class, 'updateProfile'])
            ->name('pengaturan.profile.update');

        Route::put('/pengaturan/password', [PengaturanAdminController::class, 'updatePassword'])
            ->name('pengaturan.password.update');

        Route::get('/input-promo', [InputPromoAdminController::class, 'index'])
            ->name('inputpromo');

        Route::post('/input-promo/aktif', [InputPromoAdminController::class, 'activate'])
            ->name('inputpromo.activate');

        Route::delete('/input-promo/aktif', [InputPromoAdminController::class, 'deactivate'])
            ->name('inputpromo.deactivate');

        Route::get('/ulasan-saran', [UlasanAdminController::class, 'index'])
            ->name('ulasan-saran');

        Route::put('/ulasan-saran/{ulasan_id}/status', [UlasanAdminController::class, 'updateStatus'])
            ->name('ulasan-saran.status');

        Route::get('/ulasanadmin', function () {
            return redirect()->route('admin.ulasan-saran');
        })->name('ulasanadmin');
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

        Route::get('/history', [PBookingController::class, 'history'])
            ->name('history');

        Route::get('/booking', [PBookingController::class, 'index'])
            ->name('booking');

        Route::match(['post', 'patch'], '/booking/{booking}/update-status', [
            PBookingController::class,
            'updateStatus',
        ])->name('booking.updateStatus');

        Route::get('/notifikasi', [NotifikasiController::class, 'index'])
            ->name('notifikasi');

        Route::put('/notifikasi/{id}/dibaca', [NotifikasiController::class, 'markAsRead'])
            ->name('notifikasi.dibaca');

        Route::post('/notifikasi/{id}/dismiss', [NotifikasiController::class, 'dismiss'])
            ->name('notifikasi.dismiss');

        Route::get('/notifikasi/{id}/dibaca', function () {
            return redirect()->route('pegawai.notifikasi');
        });

        Route::get('/profile', [PProfileController::class, 'index'])
            ->name('profile');

        Route::get('/profile/edit', [PProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::put('/profile/update', [PProfileController::class, 'update'])
            ->name('profile.update');

        Route::put('/profile/password-update', [PProfileController::class, 'updatePassword'])
            ->name('profile.password');

        Route::get('/jadwal', [JadwalPegawaiController::class, 'index'])
            ->name('jadwal-kerja');
    });


// =====================
// PELANGGAN
// =====================

Route::middleware(['auth', 'role:pelanggan,owner,pegawai,admin'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {
        Route::get('/profile', function () {
            return view('pelanggan.profile');
        })->name('profile');

        Route::get('/booking/{booking_id}/ulasan', [UlasanController::class, 'create'])->name('booking.ulasan');
        Route::post('/booking/{booking_id}/ulasan', [UlasanController::class, 'store'])->name('booking.ulasan.store');

        Route::get('/promo/data', [PromoController::class, 'index'])
        ->name('pelanggan.promo.data');

        Route::get('/booking/paket/{paket_id}/{cabang_id}', [BookingController::class, 'createFromPaket'])
            ->name('booking.paket')
            ->whereNumber(['paket_id', 'cabang_id']);

        Route::get('/booking/create/{layanan_cabang_id}', [BookingController::class, 'create'])
            ->name('booking.create');

        Route::post('/booking/store', [BookingController::class, 'store'])
            ->name('booking.store');

        Route::get('/bookings', [BookingController::class, 'history'])
            ->name('bookings');

        Route::get('/booking/{booking_id}', [BookingController::class, 'show'])
            ->name('booking.show');

        Route::get('/booking/{booking_id}/reschedule', [BookingController::class, 'showReschedule'])
            ->name('booking.reschedule');

        Route::post('/booking/{booking_id}/reschedule', [BookingController::class, 'processReschedule'])
            ->name('booking.reschedule.process');

        Route::get('/payment/{booking_id}', [PaymentController::class, 'show'])
            ->name('payment.show');

        Route::post('/payment/{booking_id}/process', [PaymentController::class, 'process'])
            ->name('payment.process');

        Route::get('/payment/{booking_id}/success', [PaymentController::class, 'success'])
            ->name('payment.success');
    });


// =====================
// PROFILE
// =====================

Route::get('/profile', [ProfileController::class, 'index'])
    ->name('profile');

Route::put('/profile/update', [ProfileController::class, 'update'])
    ->name('profile.update');

Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
    ->name('profile.password');