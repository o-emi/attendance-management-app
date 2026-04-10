<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController as FortifyLogin;
use Laravel\Fortify\Http\Controllers\RegisteredUserController as FortifyRegister;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class AuthController extends Controller
{
    public function loginRedirect(LoginRequest $request)
    {
        app(FortifyLogin::class)->store($request);

        $user = auth()->user();

        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.index');
        }

        if ($user) {
            return redirect()->route('attendance.index');
        }

        return back();
    }

    public function registerRedirect(Request $request)
    {
        app(FortifyRegister::class)->store(
            $request,
            app(CreatesNewUsers::class)
        );
        return redirect()->route('attendance.index');
    }

    public function logoutRedirect(Request $request)
    {
        $isAdmin = auth()->user()?->role === 'admin';

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $isAdmin
            ? redirect()->route('admin.auth.login')
            : redirect()->route('login');
        }
}
