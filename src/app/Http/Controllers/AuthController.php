<?php

namespace App\Http\Controllers;

use Laravel\Fortify\Http\Requests\LoginRequest;
use Laravel\Fortify\Http\Requests\RegisterRequest;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController as FortifyLogin;
use Laravel\Fortify\Http\Controllers\RegisteredUserController as FortifyRegister;

class AuthController extends Controller
{
    public function loginRedirect(LoginRequest $request)
    {
        app(FortifyLogin::class)->store($request);
        return redirect('/attendance/clock_in');
    }

    public function registerRedirect(RegisterRequest $request)
    {
        app(FortifyRegister::class)->store($request);
        return redirect('/attendance/clock_in');
    }
}