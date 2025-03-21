<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\CourseClassController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClassRegistrationController;


// Đăng nhập & đăng xuất
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Nhóm route yêu cầu đăng nhập
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý giảng viên (users)
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index'); // Danh sách
        Route::get('/create', [UserController::class, 'create'])->name('users.create'); // Form thêm mới
        Route::post('/', [UserController::class, 'store'])->name('users.store'); // Lưu giảng viên
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit'); // Sửa giảng viên
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update'); // Cập nhật
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy'); // Xóa
        Route::get('/profile', [UserController::class, 'profile'])->name('users.profile');
    });

    // Quản lý sinh viên
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('students.index');
        Route::get('/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/', [StudentController::class, 'store'])->name('students.store');
        Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/{id}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
        Route::get('/{id}/face', [StudentController::class, 'uploadFace'])->name('students.uploadFace');
        Route::post('/{id}/face', [StudentController::class, 'uploadFacePost'])->name('students.uploadFacePost');
        Route::get('/info', [StudentController::class, 'info'])->name('students.info');
    });

    // Quản lý môn học
    Route::prefix('subjects')->group(function () {
        Route::get('/', [SubjectController::class, 'index'])->name('subjects.index');
        Route::get('/create', [SubjectController::class, 'create'])->name('subjects.create');
        Route::post('/', [SubjectController::class, 'store'])->name('subjects.store');
        Route::get('/{id}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
        Route::put('/{id}', [SubjectController::class, 'update'])->name('subjects.update');
        Route::delete('/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
    });

    // Quản lý lớp tín chỉ
    Route::prefix('course-classes')->group(function () {
        Route::get('/', [CourseClassController::class, 'index'])->name('course_classes.index');
        Route::get('/create', [CourseClassController::class, 'create'])->name('course_classes.create');
        Route::post('/', [CourseClassController::class, 'store'])->name('course_classes.store');
        Route::get('/{id}/edit', [CourseClassController::class, 'edit'])->name('course_classes.edit');
        Route::put('/{id}', [CourseClassController::class, 'update'])->name('course_classes.update');
        Route::delete('/{id}', [CourseClassController::class, 'destroy'])->name('course_classes.destroy');
    });

    // Quản lý lịch giảng dạy
    Route::prefix('schedules')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/create', [ScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/', [ScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/{id}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('/{id}', [ScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/{id}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    });

    // Quản lý điểm danh
    Route::prefix('attendances')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('attendances.index'); // Xem danh sách điểm danh
        Route::get('/select', [AttendanceController::class, 'select'])->name('attendances.select'); // Xem danh sách điểm danh
        Route::get('/mark', [AttendanceController::class, 'mark'])->name('attendances.mark'); // Điểm danh
        Route::post('/mark', [AttendanceController::class, 'markPost'])->name('attendances.markPost'); // Điểm danh
        Route::get('/history', [AttendanceController::class, 'history'])->name('attendances.history'); // Lịch sử điểm danh
        Route::get('/confirm', [AttendanceController::class, 'confirm'])->name('attendances.confirm'); // Xác nhận điểm danh
        Route::get('/{schedule_id}/schedule', [AttendanceController::class, 'show'])->name('attendances.show');
        Route::post('/update-status', [AttendanceController::class, 'updateStatus'])->name('attendances.updateStatus');

    });

    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');

    Route::prefix('course-classes/{course_class}/registrations')->group(function () {
        Route::get('/', [ClassRegistrationController::class, 'index'])->name('class_registrations.index');
        Route::get('/create', [ClassRegistrationController::class, 'create'])->name('class_registrations.create');
        Route::post('/', [ClassRegistrationController::class, 'store'])->name('class_registrations.store');
        Route::delete('/{id}', [ClassRegistrationController::class, 'destroy'])->name('class_registrations.destroy');
    });
});