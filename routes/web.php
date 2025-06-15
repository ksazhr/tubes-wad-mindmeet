<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\FeedbackController; // Ini FeedbackController yang kita revisi
use App\Http\Controllers\API\JadwalController;
use App\Http\Controllers\API\KonselorController;
use App\Http\Controllers\API\MahasiswaController;


Route::get('/', function () {
    return redirect()->route('login');
});

// ========== AUTH ==========

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
// Rute untuk memproses pendaftaran
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// ========== MAHASISWA ROUTES ==========
// Rute-rute ini membutuhkan autentikasi dan peran 'mahasiswa'.
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/home', [BookingController::class, 'home'])->name('home');
    Route::get('/schedule', [BookingController::class, 'lihatJadwal'])->name('schedule');
    Route::get('/appointment', [BookingController::class, 'showBookingForm'])->name('appointment');

    // **PERBAIKAN DI SINI:**
    // Mengubah nama rute untuk konsistensi:
    // GET untuk menampilkan form feedback
    Route::get('/feedback', [FeedbackController::class, 'showForm'])->name('feedback'); // sebelumnya feedback.store
    // POST untuk menyimpan feedback
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store'); // sebelumnya feedback

    Route::post('/appointment/submit', [BookingController::class, 'bookingJadwal'])->name('booking.submit');
    Route::put('/booking/{id}/reschedule', [BookingController::class, 'reschedule'])->name('booking.reschedule');
    Route::put('/booking/{id}/cancel', [BookingController::class, 'cancelBooking'])->name('booking.cancel');
});

// ========== KONSELOR ROUTES ==========
Route::middleware(['auth', 'role:konselor'])->prefix('konselor')->group(function () {
    // Dashboard Konselor
    Route::get('/dashboard', [KonselorController::class, 'dashboard'])->name('konselor.dashboard');

    // Manajemen Jadwal Konselor (dihandle oleh JadwalController)
    Route::get('/my-schedules', [JadwalController::class, 'index'])->name('konselor.my_schedules');
    Route::get('/my-schedules/create', [JadwalController::class, 'create'])->name('konselor.my_schedules.create');
    Route::post('/my-schedules', [JadwalController::class, 'store'])->name('konselor.my_schedules.store');
    Route::get('/my-schedules/{id}', [JadwalController::class, 'show'])->name('konselor.my_schedules.show');

    // **Rute yang Hilang - Ditambahkan Kembali:**
    // Rute untuk MENAMPILKAN FORM EDIT JADWAL
    Route::get('/my-schedules/{id}/edit', [JadwalController::class, 'edit'])->name('konselor.my_schedules.edit');

    Route::put('/my-schedules/{id}', [JadwalController::class, 'update'])->name('konselor.my_schedules.update');
    Route::delete('/my-schedules/{id}', [JadwalController::class, 'destroy'])->name('konselor.my_schedules.destroy');

    // Rute Feedback Konselor
    // **PERBAIKAN DI SINI:**
    // Middleware 'auth' dan 'role:konselor' sudah di group, jadi tidak perlu lagi 'middleware('auth:konselor')'
    Route::get('/feedback', [FeedbackController::class, 'showFeedback'])->name('konselor.feedback');

});

// ========== ADMIN ROUTES ==========
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [BookingController::class, 'dashboardAdmin'])->name('manage.booking');

    // Booking management
    Route::get('/appointments', [BookingController::class, 'getAppointmentsAdmin'])->name('appointments.index');
    Route::get('/appointments/{id}/edit', [BookingController::class, 'getAppointmentAdmin'])->name('appointments.edit');
    Route::put('/appointments/{id}', [BookingController::class, 'updateAppointmentAdmin'])->name('appointments.update');
    Route::delete('/appointments/{id}', [BookingController::class, 'deleteAppointmentAdmin'])->name('appointments.destroy');

    // Konselor management
    Route::get('/counselors', [KonselorController::class, 'index'])->name('manage.counselor');
    Route::get('/counselors/create', [KonselorController::class, 'create'])->name('counselor.create');
    Route::post('/counselors', [KonselorController::class, 'store'])->name('counselor.store');
    Route::get('/counselors/{konselor}/edit', [KonselorController::class, 'edit'])->name('counselor.edit');
    Route::put('/counselors/{konselor}', [KonselorController::class, 'update'])->name('counselor.update');
    Route::delete('/counselors/{konselor}', [KonselorController::class, 'destroy'])->name('counselor.destroy');

    // Jadwal management
    Route::get('/schedules', [JadwalController::class, 'indexAdmin'])->name('manage.schedule');

    // Mahasiswa management
    Route::get('/students', [MahasiswaController::class, 'index'])->name('manage.student');

    // Feedback management
    Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('manage.feedback');
});