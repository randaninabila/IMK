<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\ServiceController;
use App\Http\Controllers\Owner\EmployeeController;
use App\Http\Controllers\Owner\CustomerController;

use App\Http\Controllers\User\GalleryController;
use App\Http\Controllers\User\UserServiceController;

// =====================
// PUBLIC / USER
// =====================

// Home
Route::get('/', [GalleryController::class, 'index'])->name('home');

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
Route::get('/service', [UserServiceController::class, 'index'])->name('service.index');
Route::get('/service/{slug}', [UserServiceController::class, 'show'])->name('service.detail');

// Specialist
Route::get('/specialist', function () {
    return view('user.specialist.specialist');
});

// Gallery
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