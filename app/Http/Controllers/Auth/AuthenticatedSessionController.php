<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Illuminate\Support\Facades\Crypt;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request)
    {
        if ($request->get('email') && $request->get('phone') && $request->get('password')) {
            $email = Crypt::decryptString($request->get('email'));
            $phone = Crypt::decryptString($request->get('phone'));
            $password = Crypt::decryptString($request->get('password'));

            $user = User::where(function ($q) use ($email, $phone, $password) {
                $q->where('email', $email);
                $q->where('phone', $phone);
                $q->where('password', $password);
            })->first();

            if ($user) {
                Auth::login($user);
            }
            return Inertia::location(route('dashboard'));
        } else {
            return Inertia::render('Auth/Login', [
                'canResetPassword' => Route::has('password.request'),
                'status' => session('status'),
            ]);
        }

    }

    /**
     * @throws ValidationException
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return Inertia::location(route('dashboard'));
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Inertia::location(route('index'));
    }
}
