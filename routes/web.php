<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Album;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;

use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\ServiceController;
use App\Http\Controllers\Owner\EmployeeController;
use App\Http\Controllers\Owner\CustomerController;

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

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\PenjadwalanAdminController;
use App\Http\Controllers\Admin\PegawaiAdminController;
use App\Http\Controllers\Admin\PelangganAdminController;
use App\Http\Controllers\Admin\UlasanAdminController;
use App\Http\Controllers\Admin\PengaturanAdminController;
use App\Http\Controllers\Admin\InputPromoAdminController;

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

Route::get('/testimoni', function () {
    return view('user.testimoni.testimoni');
})->name('testimoni');

Route::get('/ulasan/inputulasan', function () {
    return view('user.ulasan.inputulasan');
})->name('ulasan.inputulasan');

Route::get('/gallery', function () {
    $albums = Album::with(['layanan', 'fotos'])->get();

    return view('user.gallery.gallery', compact('albums'));
})->name('gallery');

Route::get('/gallery/detail/{identifier?}', function ($identifier = null) {
    if (!$identifier) {
        return redirect()->route('gallery');
    }

    $album = new Album();
    $table = $album->getTable();

    if (is_numeric($identifier)) {
        $gallery = Album::with(['layanan', 'fotos'])->findOrFail($identifier);

        return view('user.gallery.gdetail', compact('gallery'));
    }

    if (Schema::hasColumn($table, 'slug')) {
        $gallery = Album::with(['layanan', 'fotos'])
            ->where('slug', $identifier)
            ->firstOrFail();

        return view('user.gallery.gdetail', compact('gallery'));
    }

    abort(404);
})->name('gallery.detail');

Route::get('/gallery/{id}', function ($id) {
    $gallery = Album::with(['layanan', 'fotos'])->findOrFail($id);

    return view('user.gallery.gdetail', compact('gallery'));
})->name('gallery.show')->whereNumber('id');

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

Route::get('/service', [ServiceDetailController::class, 'index'])
    ->name('service');

Route::get('/service/layanan/{layanan_id}', [LayananDetailController::class, 'show'])
    ->name('service.layanan')
    ->whereNumber('layanan_id');

Route::get('/service/{jenis_layanan_id}', [ServiceDetailController::class, 'show'])
    ->name('service.detail')
    ->whereNumber('jenis_layanan_id');

Route::get('/specialist', [SpecialistController::class, 'index'])
    ->name('specialist');

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

Route::get('/specialist/{pegawai_id}', [SpecialistController::class, 'show'])
    ->name('specialist.show')
    ->whereNumber('pegawai_id');

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

    Route::get('/verify-email-notice', [AuthController::class, 'verifyEmailNotice'])
        ->name('verification.custom.notice');

    Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])
        ->name('verification.custom.verify');

    Route::post('/resend-verification', [AuthController::class, 'resendVerification'])
        ->name('verification.resend');
});

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('owner.dashboard');

    Route::get('/export-pdf', [DashboardController::class, 'exportPDF'])
        ->name('owner.export-pdf');

    Route::get('/serviceo', [ServiceController::class, 'index'])
        ->name('owner.service');

    Route::get('/serviceo/edit', [ServiceController::class, 'edit'])
        ->name('owner.service.edit');

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

Route::prefix('admin')
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

Route::middleware(['auth', 'role:pegawai'])
    ->prefix('pegawai')
    ->name('pegawai.')
    ->group(function () {
        Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/history', [PBookingController::class, 'history'])
            ->name('history');

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

        Route::get('/jadwal', [JadwalPegawaiController::class, 'index'])
            ->name('jadwal-kerja');

        Route::get('/booking', [PBookingController::class, 'index'])
            ->name('booking');

        Route::match(['post', 'patch'], '/booking/{booking}/update-status', [
            PBookingController::class,
            'updateStatus',
        ])->name('booking.updateStatus');
    });

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

        Route::get('/bookings', [BookingController::class, 'history'])
            ->name('bookings');

        Route::get('/booking/{booking_id}', [BookingController::class, 'show'])
            ->name('booking.show');

        Route::get('/booking/{booking_id}/reschedule', [BookingController::class, 'showReschedule'])
            ->name('booking.reschedule');

        Route::post('/booking/{booking_id}/reschedule', [BookingController::class, 'processReschedule'])
            ->name('booking.reschedule.process');
    });

Route::get('/profile', [ProfileController::class, 'index'])
    ->name('profile');

Route::put('/profile/update', [ProfileController::class, 'update'])
    ->name('profile.update');

Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
    ->name('profile.password');