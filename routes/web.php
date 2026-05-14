<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Album;

use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\ServiceController;
use App\Http\Controllers\Owner\EmployeeController;
use App\Http\Controllers\Owner\CustomerController;

use App\Http\Controllers\User\GalleryController;
use App\Http\Controllers\User\ServiceDetailController;
use App\Http\Controllers\User\SpecialistController;
use App\Http\Controllers\User\LayananDetailController;


// =====================
// PUBLIC / USER
// =====================

// Home
Route::get('/', function () {
    $albums = Album::with(['layanan', 'fotos'])->get();
    return view('user.gallery.gallery', compact('albums'));
});

Route::get('/gallery', function () {
    $albums = Album::with(['layanan', 'fotos'])->get();
    return view('user.gallery.gallery', compact('albums'));
});


// Login & Register
Route::middleware('guest')->group(function () {

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

// Service list
Route::get('/service', function () {
    return view('user.service.service');
});

// Service detail - DINAMIS berdasarkan jenis_layanan_id
Route::get('/service/{jenis_layanan_id}', [ServiceDetailController::class, 'show'])
    ->name('service.detail')
    ->whereNumber('jenis_layanan_id');

Route::get('/specialist', [SpecialistController::class, 'index']);
 
Route::get('/service/layanan/{layanan_id}', [LayananDetailController::class, 'show'])
    ->name('service.layanan');

// Detail specialist - dari database
Route::get('/specialist/{pegawai_id}', [SpecialistController::class, 'show'])
    ->name('specialist.show')
    ->whereNumber('pegawai_id');

// =====================
// GALLERY DETAIL
// =====================
Route::get('/gallery/{id}', function ($id) {
    $gallery = Album::with(['layanan', 'fotos'])->findOrFail($id);
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

        Route::get('/dashboard', function () {
            return view('pegawai.dashboard');
        })->name('dashboard');

        Route::get('/history', function () {
            return view('pegawai.history.his1');
        })->name('history');

        Route::get('/notification', function () {
            return view('pegawai.notifikasi.not1');
        })->name('notification');

        Route::get('/profile', function () {
            return view('pegawai.profile.prof1');
        })->name('profile');

        Route::get('/jadwal', function () {
            return view('pegawai.jk.jkb');
        })->name('jadwal');

        Route::get('/booking', function () {
            return view('pegawai.booking.book1');
        })->name('booking');

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