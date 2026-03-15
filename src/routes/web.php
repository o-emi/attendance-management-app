<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\AttendanceController;

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'loginRedirect']);
    Route::post('/register', [AuthController::class, 'registerRedirect']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify_email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('attendance.index');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '認証メールを再送しました。');
    })->middleware('throttle:6,1')->name('verification.send');

    Route::post('/logout', [AuthController::class, 'logoutRedirect'])
        ->name('logout');
});

Route::middleware(['auth','verified'])->group(function () {

    Route::get('/', fn() => redirect()->route('attendance.index'));

    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->name('attendance.index');

    Route::post('/attendance', [AttendanceController::class, 'punch'])
        ->name('attendance.punch');

    Route::post('/attendance/break/start',
    [AttendanceController::class, 'breakStart'])
        ->name('attendance.break.start');

    Route::post('/attendance/break/end',
    [AttendanceController::class, 'breakEnd'])
        ->name('attendance.break.end');

    Route::get('/attendance/list', [AttendanceController::class, 'list'])
    ->name('attendance.list');

    Route::get('/attendance/{id}',
        [AttendanceController::class, 'show'])
        ->name('attendance.show');

    Route::put('/attendance/{id}',
        [AttendanceController::class, 'update'])
        ->name('attendance.update');

    });

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])
    ->name('admin.auth.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.auth.login.post');

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->name('admin.auth.logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function (){
    Route::get('/attendance/list', [AdminAttendanceController::class, 'index'])
        ->name('admin.attendance.list');

    Route::get('/attendance/{id}', [AdminAttendanceController::class, 'show'])
        ->name('admin.attendance.show');

    Route::put('/attendance/{id}', [AdminAttendanceController::class, 'update'])
        ->name('admin.attendance.update');
});
