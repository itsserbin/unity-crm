<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    final public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    final public function store(RegisterRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->plan = 'free';
        $user->subscription_expiration = Carbon::now()->addDays(30)->toDateTimeString();
        $user->password = Hash::make($request->password);
        $user->save();

        $tenant = new Tenant();
        $tenant->id = $request->domain;
        $tenant->name = $request->tanant_name;
        $tenant->user_id = $user->id;
        $tenant->save();

        $tenant->domains()->create([
            'domain' => $request->domain . '.' . env('APP_DOMAIN')
        ]);

        $tenant->run(function () use ($request) {
            User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        });

        Auth::login($user);
        event(new Registered($user));
        return Inertia::location(route('dashboard'));
    }
}
