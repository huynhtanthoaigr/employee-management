<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\User\ScheduleController as UserScheduleController;
use App\Http\Controllers\User\AttendanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Admin\AdminAttendanceController;

// ================== AUTH ==================
// Trang login mặc định
Route::get('/', function () {
    return redirect()->route('login');
});


// Login routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================== ADMIN ==================
Route::middleware(['auth', RoleMiddleware::class.':quanly'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Quản lý nhân viên
        Route::resource('employees', EmployeeController::class);

        // Quản lý lịch làm việc
        Route::get('/schedule/requests', [AdminScheduleController::class, 'requestForm'])->name('schedule.requests');
        Route::post('/schedule/requests', [AdminScheduleController::class, 'saveRequests'])->name('schedule.requests.save');
        Route::post('/schedule/generate', [AdminScheduleController::class, 'generateSchedule'])->name('schedule.generate');
        Route::get('/schedule', [AdminScheduleController::class, 'viewSchedule'])->name('schedule.view');

        // Quản lý chấm công
        Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/mark', [AdminAttendanceController::class, 'markAttendance'])->name('attendance.mark');
        Route::post('/attendance/approve', [AdminAttendanceController::class, 'approveOvertime'])->name('attendance.approve');
        Route::get('/attendance/weekly', [AdminAttendanceController::class, 'weekly'])->name('attendance.weekly');
});

// ================== USER ==================
Route::middleware(['auth', RoleMiddleware::class.':truongca|ky_thuat|ban_hang|nha_lien_hoan'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('user.dashboard');
        })->name('dashboard');

        Route::get('/schedule', [UserScheduleController::class, 'viewSchedule'])->name('schedule');

        Route::post('/attendance/checkin', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
        Route::post('/attendance/checkout', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');
        Route::post('/attendance/overtime', [AttendanceController::class, 'overtime'])->name('attendance.overtime');
});

// ================== PROFILE ==================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});