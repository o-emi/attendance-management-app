<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

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
        return redirect('/attendance/clock_in');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '認証メールを再送しました。');
    })->middleware('throttle:6,1')->name('verification.send');

});

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])
        ->name('attendance.index');

    Route::get('/attendance/clock_in', [AttendanceController::class, 'clockIn'])
        ->name('attendance.clock_in');
});
